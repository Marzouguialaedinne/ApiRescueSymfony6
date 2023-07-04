<?php

namespace App\Tests\Service\Brevo;

use App\Service\Brevo\CreateEmailAndSend;
use App\Service\Brevo\SendEmailConfirmRegistration;
use SendinBlue\Client\Model\CreateSmtpEmail;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use stdClass;

class SendEmailConfirmRegistrationTest extends KernelTestCase
{
    private SendEmailConfirmRegistration $service;
    private string $emailTo;
    private object $params;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service= new SendEmailConfirmRegistration(new CreateEmailAndSend());
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
