<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
class Association
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $addressId = null;

    #[ORM\Column]
    #[Groups(['index', 'show'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('index')]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $siret = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups('index')]
    private bool $certified = false;

    #[ORM\OneToOne(targetEntity: 'Address', cascade: ['persist', 'remove'])]
    #[Groups('index')]
    private ?Address $address = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'associations')]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: ProofDocument::class)]
    private Collection $proofDocuments;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Rescue::class)]
    private Collection $rescues;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: WithdrawalPoint::class)]
    private Collection $withdrawalPoints;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Notification::class)]
    private Collection $notifications;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->proofDocuments = new ArrayCollection();
        $this->rescues = new ArrayCollection();
        $this->withdrawalPoints = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Association
    {
        $this->id = $id;
        return $this;
    }

    public function getAddressId(): ?int
    {
        return $this->addressId;
    }

    public function setAddressId(?int $addressId): Association
    {
        $this->addressId = $addressId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Association
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Association
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Association
    {
        $this->phone = $phone;
        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): Association
    {
        $this->siret = $siret;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): Association
    {
        $this->image = $image;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Association
    {
        $this->description = $description;
        return $this;
    }

    public function isCertified(): bool
    {
        return $this->certified;
    }

    public function setCertified(bool $certified): Association
    {
        $this->certified = $certified;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): Association
    {
        $this->address = $address;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): Association
    {
        $this->users = $users;
        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAssociation($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            if ($user->getAssociations()->contains($this)) {
                $user->removeAssociation($this);
            }
        }

        return $this;
    }

    public function getProofDocuments(): Collection
    {
        return $this->proofDocuments;
    }

    public function setProofDocuments(Collection $proofDocuments): Association
    {
        $this->proofDocuments = $proofDocuments;
        return $this;
    }

    public function addProofDocument(ProofDocument $proofDocument): self
    {
        if (!$this->proofDocuments->contains($proofDocument)) {
            $this->proofDocuments[] = $proofDocument;
            $proofDocument->setAssociation($this);
        }

        return $this;
    }

    public function removeProofDocument(ProofDocument $proofDocument): self
    {
        if ($this->proofDocuments->contains($proofDocument)) {
            $this->proofDocuments->removeElement($proofDocument);
            if ($proofDocument->getAssociation() === $this) {
                $proofDocument->setAssociation(null);
            }
        }

        return $this;
    }

    public function getRescues(): Collection
    {
        return $this->rescues;
    }

    public function setRescues(Collection $rescues): Association
    {
        $this->rescues = $rescues;
        return $this;
    }

    public function addRescue(Rescue $rescue): self
    {
        if (!$this->rescues->contains($rescue)) {
            $this->rescues[] = $rescue;
            $rescue->setAssociation($this);
        }

        return $this;
    }

    public function removeRescue(Rescue $rescue): self
    {
        if ($this->rescues->contains($rescue)) {
            $this->rescues->removeElement($rescue);
            if ($rescue->getAssociation() === $this) {
                $rescue->setAssociation(null);
            }
        }

        return $this;
    }

    public function getWithdrawalPoints(): Collection
    {
        return $this->withdrawalPoints;
    }

    public function setWithdrawalPoints(Collection $withdrawalPoints): Association
    {
        $this->withdrawalPoints = $withdrawalPoints;
        return $this;
    }

    public function addWithdrawalPoint(WithdrawalPoint $withdrawalPoint): self
    {
        if (!$this->withdrawalPoints->contains($withdrawalPoint)) {
            $this->withdrawalPoints[] = $withdrawalPoint;
            $withdrawalPoint->setAssociation($this);
        }

        return $this;
    }

    public function removeWithdrawalPoint(WithdrawalPoint $withdrawalPoint): self
    {
        if ($this->withdrawalPoints->contains($withdrawalPoint)) {
            $this->withdrawalPoints->removeElement($withdrawalPoint);
            if ($withdrawalPoint->getAssociation() === $this) {
                $withdrawalPoint->setAssociation(null);
            }
        }

        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function setMessages(Collection $messages): Association
    {
        $this->messages = $messages;
        return $this;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAssociation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            if ($message->getAssociation() === $this) {
                $message->setAssociation(null);
            }
        }

        return $this;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function setNotifications(Collection $notifications): Association
    {
        $this->notifications = $notifications;
        return $this;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setAssociation($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            if ($notification->getAssociation() === $this) {
                $notification->setAssociation(null);
            }
        }

        return $this;
    }
}
