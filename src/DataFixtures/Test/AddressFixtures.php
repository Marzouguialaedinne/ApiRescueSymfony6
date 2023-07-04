<?php

namespace App\DataFixtures\Test;

use App\DataFixtures\Trait\HasTestingPurposes;
use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements FixtureGroupInterface
{
    use HasTestingPurposes;

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $address = new Address();
            $address
                ->setStreet($i . ' rue ABC')
                ->setZipcode('75000')
                ->setCity('Paris')
                ->setDepartment('Department')
                ->setRegion('Region')
                ->setCountry('France');
            $manager->persist($address);
        }
        $manager->flush();

    }
}