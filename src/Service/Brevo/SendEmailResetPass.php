<?php

namespace App\Service\Brevo;

use SendinBlue\Client\Model\CreateSmtpEmail;
use SendinBlue\Client\ApiException;

class SendEmailResetPass implements SendEmailInterface
{
    private const TEMPLATE_RESET_PASS = 2;

    public function __construct(private readonly CreateEmailAndSend $createEmailAndSend) {}

    /**
     * @param string $emailTo
     * @param object|null $params // Needs ['firstname', token]
     * @return CreateSmtpEmail
     * @throws ApiException
     */
    public function sendEmail(string $emailTo, ?object $params): CreateSmtpEmail
    {
        // Add necessary field
        $params->link = $_ENV['APP_URL'] . "changementmotdepasse/" . $params->token;

        // Init email and send
        return $this->createEmailAndSend->execute(self::TEMPLATE_RESET_PASS, $emailTo, $params);
    }
}