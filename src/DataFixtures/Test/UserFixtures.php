<?php

namespace App\DataFixtures\Test;

use App\Config\UserRoleEnum;
use App\DataFixtures\Trait\HasTestingPurposes;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    use HasTestingPurposes;

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $professional = (bool)$i % 6;
            $deleted = (bool)$i % 9;

            $user = new User();
            $user
                ->setFirstname($i . 'John')
                ->setLastname('Doe')
                ->setEmail($i . 'john@doe.com')
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setPhone('0123456789')
                ->setBirthdate(new DateTime('2000-01-06'))
                ->setProfessional($professional)
                ->setCompanyName($professional ? 'Company ' . $i : null)
                ->setSiret($professional ? 'siret' . $i : null)
                ->setVerified(!($i % 5))
                ->setDeletedAt($deleted ? new DateTime() : null)
                ->setRoles([
                    UserRoleEnum::USER->value,
                ]);
            $manager->persist($user);
        }
        $manager->flush();
    }
}