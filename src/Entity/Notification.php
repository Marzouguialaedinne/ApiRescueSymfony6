<?php

namespace App\Entity;

use App\Config\NotificationTypeEnum;
use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $orderId = null;

    #[ORM\Column(nullable: true)]
    private ?int $associationId = null;

    #[ORM\Column]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?string $notificationType = null;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: 'notifications')]
    private ?Association $association = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'notifications')]
    private ?Order $order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Notification
    {
        $this->id = $id;
        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): Notification
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getAssociationId(): ?int
    {
        return $this->associationId;
    }

    public function setAssociationId(?int $associationId): Notification
    {
        $this->associationId = $associationId;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Notification
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Notification
    {
        $this->content = $content;
        return $this;
    }

    public function getNotificationType(): ?string
    {
        return $this->notificationType;
    }

    public function setNotificationType(?string $notificationType): Notification
    {
        if (!NotificationTypeEnum::tryFrom($notificationType)) {
            throw new InvalidArgumentException(sprintf('Notification type "%s" does not exist.', $notificationType));
        }

        $this->notificationType = $notificationType;
        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): Notification
    {
        $this->association = $association;
        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): Notification
    {
        $this->order = $order;
        return $this;
    }
}
