<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'handleException'];
    }

    public function handleException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        $response = new JsonResponse(
            [
                'code' => $code,
                'error' => $exception->getMessage(),
            ],
            $code,
        );

        $event->setResponse($response);
    }
}