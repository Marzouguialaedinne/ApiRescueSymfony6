<?php

namespace App\Service\Brevo;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;

abstract class BrevoClient
{
    private string $key;
    protected TransactionalEmailsApi $apiEmailInstance;

    public function __construct()
    {
        // Prepare API Instance
        $this->key = $_ENV['BREVO_KEY'];
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->key);
        $this->apiEmailInstance = new TransactionalEmailsApi(null, $config);
    }
}