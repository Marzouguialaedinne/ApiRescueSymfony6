<?php

namespace App\Entity;

use App\Config\PaymentMethodEnum;
use App\Repository\RescueRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RescueRepository::class)]
class Rescue
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $associationId = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?string $image = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?DateTime $startDate = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?DateTime $endDate = null;

    #[ORM\Column]
    #[Groups('show')]
    private ?DateTime $limitRefundDate = null;

    #[ORM\Column]
    #[Groups('show')]
    private ?float $priceInEuros = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups('show')]
    private array $paymentMethods = [];

    #[ORM\Column]
    #[Groups('show')]
    private ?string $henOrigin = null;

    #[ORM\Column]
    #[Groups('show')]
    private ?string $henRace = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?string $henDescription = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?int $henQuantity = null;

    #[ORM\Column]
    #[Groups('show')]
    private ?int $henLimitPerClient = null;

    #[Groups(['index', 'show'])]
    private ?int $daysLeft = null;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: 'rescues')]
    #[Groups(['index', 'show'])]
    private ?Association $association = null;

    #[ORM\OneToMany(mappedBy: 'rescue', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'rescue', targetEntity: WithdrawalDate::class)]
    #[Groups('show')]
    private Collection $withdrawalDates;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->withdrawalDates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Rescue
    {
        $this->id = $id;
        return $this;
    }

    public function getAssociationId(): ?int
    {
        return $this->associationId;
    }

    public function setAssociationId(?int $associationId): Rescue
    {
        $this->associationId = $associationId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Rescue
    {
        $this->name = $name;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): Rescue
    {
        $this->image = $image;
        return $this;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): Rescue
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): Rescue
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getLimitRefundDate(): ?DateTime
    {
        return $this->limitRefundDate;
    }

    public function setLimitRefundDate(?DateTime $limitRefundDate): Rescue
    {
        $this->limitRefundDate = $limitRefundDate;
        return $this;
    }

    public function getPriceInEuros(): ?float
    {
        return $this->priceInEuros;
    }

    public function setPriceInEuros(?float $priceInEuros): Rescue
    {
        $this->priceInEuros = $priceInEuros;
        return $this;
    }

    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethods(array $paymentMethods): Rescue
    {
        foreach ($paymentMethods as $paymentMethod) {
            if (!PaymentMethodEnum::tryFrom($paymentMethod)) {
                throw new InvalidArgumentException(sprintf('Payment method "%s" does not exist.', $paymentMethod));
            }
        }

        $this->paymentMethods = $paymentMethods;
        return $this;
    }

    public function addPaymentMethod(string $paymentMethod): self
    {
        if (!PaymentMethodEnum::tryFrom($paymentMethod)) {
            throw new InvalidArgumentException(sprintf('Payment method "%s" does not exist.', $paymentMethod));
        }

        if (!in_array($paymentMethod, $this->paymentMethods)) {
            $this->paymentMethods[] = $paymentMethod;
        }

        return $this;
    }

    public function removePaymentMethod(string $paymentMethod): self
    {
        if ($key = array_search($paymentMethod, $this->paymentMethods)) {
            unset($this->paymentMethods[$key]);
        }

        return $this;
    }

    public function getHenOrigin(): ?string
    {
        return $this->henOrigin;
    }

    public function setHenOrigin(?string $henOrigin): Rescue
    {
        $this->henOrigin = $henOrigin;
        return $this;
    }

    public function getHenRace(): ?string
    {
        return $this->henRace;
    }

    public function setHenRace(?string $henRace): Rescue
    {
        $this->henRace = $henRace;
        return $this;
    }

    public function getHenDescription(): ?string
    {
        return $this->henDescription;
    }

    public function setHenDescription(?string $henDescription): Rescue
    {
        $this->henDescription = $henDescription;
        return $this;
    }

    public function getHenQuantity(): ?int
    {
        return $this->henQuantity;
    }

    public function setHenQuantity(?int $henQuantity): Rescue
    {
        $this->henQuantity = $henQuantity;
        return $this;
    }

    public function getHenLimitPerClient(): ?int
    {
        return $this->henLimitPerClient;
    }

    public function setHenLimitPerClient(?int $henLimitPerClient): Rescue
    {
        $this->henLimitPerClient = $henLimitPerClient;
        return $this;
    }

    public function getDaysLeft(): ?int
    {
        return round((strtotime($this->getEndDate()->format('Y-m-d H:i:s')) - time()) / (60 * 60 * 24));
    }

    public function setDaysLeft(?int $daysLeft): Rescue
    {
        $this->daysLeft = $daysLeft;
        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): Rescue
    {
        $this->association = $association;
        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function setOrders(Collection $orders): Rescue
    {
        $this->orders = $orders;
        return $this;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setRescue($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            if ($order->getRescue() === $this) {
                $order->setRescue(null);
            }
        }

        return $this;
    }

    public function getWithdrawalDates(): Collection
    {
        return $this->withdrawalDates;
    }

    public function setWithdrawalDates(Collection $withdrawalDates): Rescue
    {
        $this->withdrawalDates = $withdrawalDates;
        return $this;
    }

    public function addWithdrawalDate(WithdrawalDate $withdrawalDate): self
    {
        if (!$this->withdrawalDates->contains($withdrawalDate)) {
            $this->withdrawalDates[] = $withdrawalDate;
            $withdrawalDate->setRescue($this);
        }

        return $this;
    }

    public function removeWithdrawalDate(WithdrawalDate $withdrawalDate): self
    {
        if ($this->withdrawalDates->contains($withdrawalDate)) {
            $this->withdrawalDates->removeElement($withdrawalDate);
            if ($withdrawalDate->getRescue() === $this) {
                $withdrawalDate->setRescue(null);
            }
        }

        return $this;
    }
}
