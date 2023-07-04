<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $userId = null;

    #[ORM\Column(nullable: true)]
    private ?int $associationId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: "messages")]
    private ?Association $association = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Message
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): Message
    {
        $this->userId = $userId;
        return $this;
    }

    public function getAssociationId(): ?int
    {
        return $this->associationId;
    }

    public function setAssociationId(?int $associationId): Message
    {
        $this->associationId = $associationId;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Message
    {
        $this->content = $content;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Message
    {
        $this->user = $user;
        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): Message
    {
        $this->association = $association;
        return $this;
    }
}
