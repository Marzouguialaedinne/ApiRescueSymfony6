<?php

namespace App\Service\Security\Token;

use App\Config\TokenTypeEnum;
use App\Entity\User;
use App\Repository\TokensRegistryRepository;

class TokenValidityChecker
{
    private ?User $linkedUser;

    /**
     * @param TokensRegistryRepository $tokensRegistryRepository
     */
    public function __construct(
        private readonly TokensRegistryRepository $tokensRegistryRepository,
    ) {}

    /**
     * @param TokenTypeEnum $tokenType
     * @param string $token
     * @return bool
     */
    public function check(TokenTypeEnum $tokenType, string $token): bool
    {
        // Check if token with expiration date
        match($tokenType){
            TokenTypeEnum::TOKEN_CONFIRM_REGISTER => $expiration = false,
            TokenTypeEnum::TOKEN_RESET_PASS => $expiration = true
        };

        // Return if token is valid bool
        $this->linkedUser = $this->tokensRegistryRepository->findIfValidToken($tokenType->value, $token, $expiration)?->getLinkedUser();

        return (bool) $this->linkedUser;
    }

    public function getUser(): ?User
    {
        return $this->linkedUser;
    }
}