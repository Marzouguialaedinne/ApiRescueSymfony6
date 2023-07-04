<?php

namespace App\Entity;

use App\Config\UserRoleEnum;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use App\Entity\Trait\TimestampableEntity;
use InvalidArgumentException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('index')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $addressId = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $firstname = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $lastname = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups('index')]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?DateTime $birthdate = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $companyName = null;

    #[ORM\Column(nullable: true)]
    #[Groups('index')]
    private ?string $siret = null;

    #[ORM\Column]
    #[Groups('index')]
    private bool $professional = false;

    #[ORM\Column]
    #[Groups('index')]
    private bool $verified = false;

    #[ORM\Column(type: Types::JSON)]
    #[Groups('index')]
    private array $roles = [];

    #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'])]
    #[Groups('index')]
    private ?Address $address = null;

    #[ORM\ManyToMany(targetEntity: Association::class, mappedBy: 'users', cascade: ['persist'])]
    #[Groups('index')]
    private Collection $associations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\OneToOne(inversedBy: 'linkedUser', cascade: ['persist', 'remove'])]
    private ?TokensRegistry $tokens = null;

    public function __construct()
    {
        $this->associations = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getAddressId(): ?int
    {
        return $this->addressId;
    }

    public function setAddressId(?int $addressId): User
    {
        $this->addressId = $addressId;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?DateTime $birthdate): User
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): User
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): User
    {
        $this->siret = $siret;
        return $this;
    }

    public function isProfessional(): bool
    {
        return $this->professional;
    }

    public function setProfessional(bool $professional): User
    {
        $this->professional = $professional;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): User
    {
        $this->verified = $verified;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRoleEnum::USER->value;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        foreach ($roles as $role) {
            if (!UserRoleEnum::tryFrom($role)) {
                throw new InvalidArgumentException(sprintf('User role "%s" does not exist.', $role));
            }
        }

        $this->roles = $roles;
        return $this;
    }

    public function addRole(string $role): self
    {
        if (!UserRoleEnum::tryFrom($role)) {
            throw new InvalidArgumentException(sprintf('User role "%s" does not exist.', $role));
        }

        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if ($key = array_search($role, $this->roles)) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): User
    {
        $this->address = $address;
        return $this;
    }

    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    public function setAssociations(Collection $associations): User
    {
        $this->associations = $associations;
        return $this;
    }

    public function addAssociation(Association $association): self
    {
        if (!$this->associations->contains($association)) {
            $this->associations[] = $association;
            $association->addUser($this);
        }

        return $this;
    }

    public function removeAssociation(Association $association): self
    {
        if ($this->associations->contains($association)) {
            $this->associations->removeElement($association);
            if ($association->getUsers()->contains($this)) {
                $association->removeUser($this);
            }
        }

        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function setOrders(Collection $orders): User
    {
        $this->orders = $orders;
        return $this;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function setMessages(Collection $messages): User
    {
        $this->messages = $messages;
        return $this;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * Returning a salt is only needed if you are not using a modern hashing algorithm (e.g. bcrypt or sodium) in your security.yaml. Temporary solution
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTokens(): ?TokensRegistry
    {
        return $this->tokens;
    }

    public function setTokens(?TokensRegistry $tokens): self
    {
        $this->tokens = $tokens;

        return $this;
    }
}
