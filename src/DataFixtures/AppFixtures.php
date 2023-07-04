<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    private ?ContainerInterface $container;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail($this->container->getParameter('connector_email'))
            ->setPassword($this->hasher->hashPassword($user, $this->container->getParameter('connector_password')));
        $manager->persist($user);
        $manager->flush();
    }
}