<?php

namespace App\Entity;

use App\Repository\WithdrawalPointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WithdrawalPointRepository::class)]
class WithdrawalPoint
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $addressId = null;

    #[ORM\Column]
    private ?int $associationId = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToOne(targetEntity: Address::class)]
    #[Groups(['index', 'show'])]
    private ?Address $address = null;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: 'withdrawalPoints')]
    private ?Association $association = null;

    #[ORM\OneToMany(mappedBy: 'withdrawalPoint', targetEntity: WithdrawalDate::class)]
    private Collection $withdrawalDates;

    public function __construct()
    {
        $this->withdrawalDates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): WithdrawalPoint
    {
        $this->id = $id;
        return $this;
    }

    public function getAddressId(): ?int
    {
        return $this->addressId;
    }

    public function setAddressId(?int $addressId): WithdrawalPoint
    {
        $this->addressId = $addressId;
        return $this;
    }

    public function getAssociationId(): ?int
    {
        return $this->associationId;
    }

    public function setAssociationId(?int $associationId): WithdrawalPoint
    {
        $this->associationId = $associationId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): WithdrawalPoint
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): WithdrawalPoint
    {
        $this->description = $description;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): WithdrawalPoint
    {
        $this->address = $address;
        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): WithdrawalPoint
    {
        $this->association = $association;
        return $this;
    }

    public function getWithdrawalDates(): Collection
    {
        return $this->withdrawalDates;
    }

    public function setWithdrawalDates(Collection $withdrawalDates): WithdrawalPoint
    {
        $this->withdrawalDates = $withdrawalDates;
        return $this;
    }

    public function addWithdrawalDate(WithdrawalDate $withdrawalDate): self
    {
        if (!$this->withdrawalDates->contains($withdrawalDate)) {
            $this->withdrawalDates[] = $withdrawalDate;
            $withdrawalDate->setWithdrawalPoint($this);
        }

        return $this;
    }

    public function removeWithdrawalDate(WithdrawalDate $withdrawalDate): self
    {
        if ($this->withdrawalDates->contains($withdrawalDate)) {
            $this->withdrawalDates->removeElement($withdrawalDate);
            if ($withdrawalDate->getWithdrawalPoint() === $this) {
                $withdrawalDate->setWithdrawalPoint(null);
            }
        }

        return $this;
    }
}
