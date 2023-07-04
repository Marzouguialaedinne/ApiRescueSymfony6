<?php

namespace App\Service\Brevo;

use SendinBlue\Client\ApiException;
use SendinBlue\Client\Model\CreateSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailTo;

class CreateEmailAndSend extends BrevoClient
{
    /**
     * @param int $mailTemplate
     * @param string $emailTo
     * @param object|null $params
     * @return CreateSmtpEmail
     * @throws ApiException
     */
    public function execute(int $mailTemplate, string $emailTo, ?object $params): CreateSmtpEmail
    {
        // Create email prototype
        $email = new SendSmtpEmail();
        $email->setTo([new SendSmtpEmailTo(['email' => $emailTo])])
            ->setTemplateId($mailTemplate);

        // If params added add into the mail
        if ($params) {
            $email->setParams($params);
        }

        // Send or catch exception
        try {
            $response = $this->apiEmailInstance->sendTransacEmail($email);
        } catch (ApiException $e) {
            throw new ApiException($e->getMessage());
        }

        return $response;
    }
}