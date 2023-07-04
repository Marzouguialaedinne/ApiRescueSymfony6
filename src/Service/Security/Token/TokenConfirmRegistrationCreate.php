<?php

namespace App\Service\Security\Token;

use App\Entity\TokensRegistry;
use App\Repository\TokensRegistryRepository;
use Exception;

class TokenConfirmRegistrationCreate implements TokenCreateInterface
{
    private const STRING_LENGTH = 48;

    public function __construct(private readonly TokensRegistryRepository $tokensRegistryRepository) {}

    /**
     * @param TokensRegistry $tokensRegistry
     * @return TokensRegistry
     * @throws Exception
     */
    public function create(TokensRegistry $tokensRegistry = new TokensRegistry()): TokensRegistry
    {
        // Create new uniq chain
        while (!isset($token) || $this->tokensRegistryRepository->findOneBy(['confirmRegister' => $token])) {
            $randomBytes = random_bytes(self::STRING_LENGTH);
            $token = bin2hex($randomBytes);
        }

        // Set the good field
        $tokensRegistry->setConfirmRegister($token);

        return $tokensRegistry;
    }
}