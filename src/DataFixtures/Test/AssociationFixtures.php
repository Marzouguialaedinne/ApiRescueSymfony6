<?php

namespace App\DataFixtures\Test;

use App\DataFixtures\Trait\HasTestingPurposes;
use App\Entity\Association;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AssociationFixtures extends Fixture implements FixtureGroupInterface
{
    use HasTestingPurposes;

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $association = new Association();
            $association
                ->setName('Association '.$i)
                ->setEmail('association'.$i.'@example.com')
                ->setPhone('1234568790')
                ->setSiret('0987654321')
                ->setDescription('association description')
                ->setCertified((bool)$i % 3)
            ;
            $manager->persist($association);
        }
        $manager->flush();
    }
}