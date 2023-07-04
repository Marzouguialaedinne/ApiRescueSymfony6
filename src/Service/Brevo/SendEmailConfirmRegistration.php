<?php

namespace App\Service\Brevo;

use SendinBlue\Client\Model\CreateSmtpEmail;
use SendinBlue\Client\ApiException;

class SendEmailConfirmRegistration implements SendEmailInterface
{
    private const TEMPLATE_CONFIRM_REGISTER = 1;

    public function __construct(private readonly CreateEmailAndSend $createEmailAndSend) {}

    /**
     * @param string $emailTo
     * @param object|null $params // Needs ['firstname', 'token']
     * @return CreateSmtpEmail
     * @throws ApiException
     */
    public function sendEmail(string $emailTo, ?object $params): CreateSmtpEmail
    {
        // Add necessary field
        $params->link = $_ENV['APP_URL'] . "confirmationinscription/" . $params->token;

        // Init email and send
        return $this->createEmailAndSend->execute(self::TEMPLATE_CONFIRM_REGISTER, $emailTo, $params);
    }
}