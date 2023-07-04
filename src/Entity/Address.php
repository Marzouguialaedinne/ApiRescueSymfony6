<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    #[Assert\NotBlank]
    private ?string $street = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    #[Assert\NotBlank]
    private ?string $zipcode;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    #[Assert\NotBlank]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['index', 'show'])]
    private ?string $region = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['index', 'show'])]
    private ?string $department = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    #[Assert\NotBlank]
    private ?string $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Address
    {
        $this->id = $id;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): Address
    {
        $this->street = $street;
        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): Address
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): Address
    {
        $this->city = $city;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): Address
    {
        $this->region = $region;
        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): Address
    {
        $this->department = $department;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): Address
    {
        $this->country = $country;
        return $this;
    }
}
