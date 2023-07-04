<?php

namespace App\Tests\Service\Security\Token;

use App\Entity\TokensRegistry;
use App\Repository\TokensRegistryRepository;
use App\Service\Security\Token\TokenResetPassCreate;
use App\Tests\Repository\InMemoryTokensRegistryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TokenResetPassCreateTest extends KernelTestCase
{
    private readonly TokensRegistryRepository $tokensRegistryRepo;
    private readonly TokenResetPassCreate $service;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->tokensRegistryRepo = new InMemoryTokensRegistryRepository();
        $this->service = new TokenResetPassCreate($this->tokensRegistryRepo);
    }

    /**
     * Test for create a new TokenRegistry
     * @return void
     * @throws \Exception
     */
    public function testCreateNewRegistry(): void
    {
        $response = $this->service->create();

        self::assertInstanceOf(TokensRegistry::class, $response);

        $registeredToken = $this->tokensRegistryRepo->findAll()[0];

        self::assertNotNull($registeredToken->getResetPass());
    }

    /**
     * Test with an existing TokenRegistry, update existing token with a new
     * @return void
     * @throws \Exception
     */
    public function testUpdateExistingRegistry(): void
    {
        $oldToken = 'monancientoken';

        $existingRegistry = new TokensRegistry();
        $existingRegistry->setResetPass($oldToken);

        $this->tokensRegistryRepo->save($existingRegistry, true);

        self::assertEquals($oldToken, $existingRegistry->getResetPass());

        $response = $this->service->create($existingRegistry);

        self::assertInstanceOf(TokensRegistry::class, $response);

        $registeredToken = $this->tokensRegistryRepo->findAll()[0];

        self::assertNotEquals($oldToken, $registeredToken->getResetPass());
    }
}
