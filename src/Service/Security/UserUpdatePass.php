<?php

namespace App\Service\Security;

use App\Entity\User;
use App\Repository\UserRepository;

class UserUpdatePass
{
    public function __construct(
        private readonly UserPasswordHash $passwordHash,
        private readonly UserRepository $userRepository,
    ) {}

    public function update(User $user, string $pass): void
    {
        // Add pass into user and hash
        $user->setPassword($pass);
        $user->setPassword($this->passwordHash->hash($user));

        // Persist
        $this->userRepository->save($user, true);
    }
}