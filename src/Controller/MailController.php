<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Brevo\SendEmailConfirmRegistration;
use App\Service\Brevo\SendEmailResetPass;
use App\Service\Security\Token\TokenResetPassCreate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use stdClass;

class MailController extends AbstractController
{
    #[Route('/api/mail/reset-pass', name: 'app_mail_reset_pass')]
    public function resetPass(
        Request $request,
        UserRepository $userRepository,
        TokenResetPassCreate $tokenResetPass,
        SendEmailResetPass $mailer,
    ): JsonResponse {
        $requestContent = json_decode($request->getContent());

        // Find if User exist
        /** @var User $user */
        $user = $userRepository->findOneBy(['email' => $requestContent->emailTo]);

        // If not exist return error
        if (!$user) {
            return $this->json(
                ['message' => "L'email n'existe pas."],
                Response::HTTP_NOT_FOUND
            );
        }

        // Create token reset pass
        $tokens = $tokenResetPass->create($user->getTokens());

        // Prepare params
        $params = new stdClass();
        $params->firstname = $user->getFirstname();
        $params->token = $tokens->getResetPass();

        // Send a register email confirmation
        $mailer->sendEmail($user->getEmail(), $params);

        return $this->json(
            ['message' => 'Un email de récupération de mot de passe vous a été envoyé.'],
            Response::HTTP_OK
        );
    }
}
