<?php

namespace App\Service\Security\Token;

use App\Entity\TokensRegistry;

interface TokenCreateInterface
{
    /**
     * @param TokensRegistry $tokensRegistry
     * @return TokensRegistry
     */
    public function create(TokensRegistry $tokensRegistry = new TokensRegistry()): TokensRegistry;
}