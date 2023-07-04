<?php

namespace App\Service\Security\Token;

use App\Entity\TokensRegistry;
use App\Repository\TokensRegistryRepository;
use DateTimeImmutable;
use Exception;

class TokenResetPassCreate implements TokenCreateInterface
{
    private const STRING_LENGTH = 48;
    public const VALIDITY_TIME = '+ 2 hour';

    public function __construct(private readonly TokensRegistryRepository $tokensRegistryRepository) {}

    /**
     * @param TokensRegistry $tokensRegistry
     * @return TokensRegistry
     * @throws Exception
     */
    public function create(TokensRegistry $tokensRegistry = new TokensRegistry()): TokensRegistry
    {
        // Create new uniq chain
        while (!isset($token) || $this->tokensRegistryRepository->findOneBy(['resetPass' => $token])) {
            $randomBytes = random_bytes(self::STRING_LENGTH);
            $token = bin2hex($randomBytes);
        }

        // Set the good field
        $tokensRegistry->setResetPass($token)
            ->setResetPassExpiration(new DateTimeImmutable(self::VALIDITY_TIME));

        $this->tokensRegistryRepository->save($tokensRegistry, true);

        return $tokensRegistry;
    }
}