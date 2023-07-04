<?php

namespace App\Service\Brevo;

use SendinBlue\Client\Model\CreateSmtpEmail;

interface SendEmailInterface
{
    /**
     * @param string $emailTo
     * @param object|null $params
     * @return CreateSmtpEmail
     */
    public function sendEmail(string $emailTo, ?object $params): CreateSmtpEmail;
}