<?php

namespace App\Entity;

use App\Config\OrderStatusEnum;
use App\Config\PaymentMethodEnum;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $rescueId = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?string $reference = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?string $orderStatus = null;

    #[ORM\Column]
    private ?string $paymentMethod = null;

    #[ORM\Column]
    private bool $refunded = false;

    #[ORM\ManyToOne(targetEntity: Rescue::class, inversedBy: 'orders')]
    private ?Rescue $rescue = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Notification::class)]
    private Collection $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Order
    {
        $this->id = $id;
        return $this;
    }

    public function getRescueId(): ?int
    {
        return $this->rescueId;
    }

    public function setRescueId(?int $rescueId): Order
    {
        $this->rescueId = $rescueId;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): Order
    {
        $this->userId = $userId;
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): Order
    {
        $this->reference = $reference;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): Order
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?string $orderStatus): Order
    {
        if (!OrderStatusEnum::tryFrom($orderStatus)) {
            throw new InvalidArgumentException(sprintf('Order status "%s" does not exist.', $orderStatus));
        }

        $this->orderStatus = $orderStatus;
        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): Order
    {
        if (!PaymentMethodEnum::tryFrom($paymentMethod)) {
            throw new InvalidArgumentException(sprintf('Payment method "%s" does not exist.', $paymentMethod));
        }

        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function isRefunded(): bool
    {
        return $this->refunded;
    }

    public function setRefunded(bool $refunded): Order
    {
        $this->refunded = $refunded;
        return $this;
    }

    public function getRescue(): ?Rescue
    {
        return $this->rescue;
    }

    public function setRescue(?Rescue $rescue): Order
    {
        $this->rescue = $rescue;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Order
    {
        $this->user = $user;
        return $this;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function setNotifications(Collection $notifications): Order
    {
        $this->notifications = $notifications;
        return $this;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setOrder($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            if ($notification->getOrder() === $this) {
                $notification->setOrder(null);
            }
        }

        return $this;
    }
}
