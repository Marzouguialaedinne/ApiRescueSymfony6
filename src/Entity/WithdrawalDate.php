<?php
namespace App\Entity;

use App\Repository\WithdrawalDateRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WithdrawalDateRepository::class)]
class WithdrawalDate
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $rescueId = null;

    #[ORM\Column]
    private ?int $withdrawalPointId = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?DateTime $startDate = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?DateTime $endDate = null;

    #[ORM\ManyToOne(targetEntity: Rescue::class, inversedBy: 'withdrawalDates')]
    private ?Rescue $rescue = null;

    #[ORM\ManyToOne(targetEntity: WithdrawalPoint::class, inversedBy: 'withdrawalDates')]
    #[Groups(['index', 'show'])]
    private ?WithdrawalPoint $withdrawalPoint = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): WithdrawalDate
    {
        $this->id = $id;
        return $this;
    }

    public function getRescueId(): ?int
    {
        return $this->rescueId;
    }

    public function setRescueId(?int $rescueId): WithdrawalDate
    {
        $this->rescueId = $rescueId;
        return $this;
    }

    public function getWithdrawalPointId(): ?int
    {
        return $this->withdrawalPointId;
    }

    public function setWithdrawalPointId(?int $withdrawalPointId): WithdrawalDate
    {
        $this->withdrawalPointId = $withdrawalPointId;
        return $this;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): WithdrawalDate
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): WithdrawalDate
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getRescue(): ?Rescue
    {
        return $this->rescue;
    }

    public function setRescue(?Rescue $rescue): WithdrawalDate
    {
        $this->rescue = $rescue;
        return $this;
    }

    public function getWithdrawalPoint(): ?WithdrawalPoint
    {
        return $this->withdrawalPoint;
    }

    public function setWithdrawalPoint(?WithdrawalPoint $withdrawalPoint): WithdrawalDate
    {
        $this->withdrawalPoint = $withdrawalPoint;
        return $this;
    }
}