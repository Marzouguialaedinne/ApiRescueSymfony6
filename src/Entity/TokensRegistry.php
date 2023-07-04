<?php

namespace App\Entity;

use App\Repository\TokensRegistryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokensRegistryRepository::class)]
class TokensRegistry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $confirmRegister = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetPass = null;

    #[ORM\OneToOne(mappedBy: 'tokens', cascade: ['persist', 'remove'])]
    private ?User $linkedUser = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $resetPassExpiration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfirmRegister(): ?string
    {
        return $this->confirmRegister;
    }

    public function setConfirmRegister(?string $confirmRegister): self
    {
        $this->confirmRegister = $confirmRegister;

        return $this;
    }

    public function getResetPass(): ?string
    {
        return $this->resetPass;
    }

    public function setResetPass(?string $resetPass): self
    {
        $this->resetPass = $resetPass;

        return $this;
    }

    public function getLinkedUser(): ?User
    {
        return $this->linkedUser;
    }

    public function setLinkedUser(?User $linkedUser): self
    {
        // unset the owning side of the relation if necessary
        if ($linkedUser === null && $this->linkedUser !== null) {
            $this->linkedUser->setTokens(null);
        }

        // set the owning side of the relation if necessary
        if ($linkedUser !== null && $linkedUser->getTokens() !== $this) {
            $linkedUser->setTokens($this);
        }

        $this->linkedUser = $linkedUser;

        return $this;
    }

    public function getResetPassExpiration(): ?\DateTimeImmutable
    {
        return $this->resetPassExpiration;
    }

    public function setResetPassExpiration(?\DateTimeImmutable $resetPassExpiration): self
    {
        $this->resetPassExpiration = $resetPassExpiration;

        return $this;
    }
}
