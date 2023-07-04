<?php

namespace App\Tests\Service\Brevo;

use App\Service\Brevo\CreateEmailAndSend;
use App\Service\Brevo\SendEmailResetPass;
use SendinBlue\Client\Model\CreateSmtpEmail;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use stdClass;

class SendEmailResetPassTest extends KernelTestCase
{
    private SendEmailResetPass $service;
    private string $emailTo;
    private object $params;

    public function setUp(): void
    {
        parent::setUp();

        $this->service= new SendEmailResetPass(new CreateEmailAndSend());
        $this->emailTo = $_ENV['BREVO_MAIL_TEST'];

        $this->params = new stdClass();
        $this->params->firstname = 'John';
    }

    public function testIsSuccess()
    {
        $response = $this->service->sendEmail($this->emailTo, $this->params);

        self::assertInstanceOf(CreateSmtpEmail::class, $response);
    }
}
