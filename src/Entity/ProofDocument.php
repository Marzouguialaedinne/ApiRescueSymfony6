<?php

namespace App\Entity;

use App\Config\ProofDocumentEnum;
use App\Repository\ProofDocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableEntity;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: ProofDocumentRepository::class)]
class ProofDocument
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $associationId;

    #[ORM\Column]
    private ?string $proofDocumentType = null;

    #[ORM\Column]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?string $file = null;

    #[ORM\Column]
    private bool $validated = false;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: 'proofDocuments')]
    private ?Association $association = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): ProofDocument
    {
        $this->id = $id;
        return $this;
    }

    public function getAssociationId(): ?int
    {
        return $this->associationId;
    }

    public function setAssociationId(?int $associationId): ProofDocument
    {
        $this->associationId = $associationId;
        return $this;
    }

    public function getProofDocumentType(): ?string
    {
        return $this->proofDocumentType;
    }

    public function setProofDocumentType(?string $proofDocumentType): ProofDocument
    {
        if (ProofDocumentEnum::tryFrom($proofDocumentType)) {
            throw new InvalidArgumentException(sprintf('Proof document type "%s" does not exist.', $proofDocumentType));
        }

        $this->proofDocumentType = $proofDocumentType;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ProofDocument
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ProofDocument
    {
        $this->description = $description;
        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): ProofDocument
    {
        $this->file = $file;
        return $this;
    }

    public function isValidated(): bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): ProofDocument
    {
        $this->validated = $validated;
        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): ProofDocument
    {
        $this->association = $association;
        return $this;
    }
}
