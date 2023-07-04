<?php

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHash
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    /**
     * @param User $user
     * @return string
     */
    public function hash(User $user): string
    {
        return $this->passwordHasher->hashPassword($user, $user->getPassword());
    }
}