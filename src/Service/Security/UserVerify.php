<?php

namespace App\Service\Security;

use App\Config\TokenTypeEnum;
use App\Entity\User;
use App\Repository\TokensRegistryRepository;
use App\Repository\UserRepository;
use App\Service\Security\Token\TokenValidityChecker;

class UserVerify
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenValidityChecker $tokenChecker,
    ) {}

    /**
     * @param string $token
     * @return User|null
     */
    public function verifyByToken(string $token): ?User
    {
        // Verify Token and get linked User
        if (!$this->tokenChecker->check(TokenTypeEnum::TOKEN_CONFIRM_REGISTER, $token)) {

            return null;
        }

        $user = $this->tokenChecker->getUser();

        // Set verified at true and persist
        $user->setVerified(true);
        $this->userRepository->save($user, true);

        return $user;
    }
}