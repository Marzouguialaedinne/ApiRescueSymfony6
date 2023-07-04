<?php

namespace App\Tests\Repository;

use App\Entity\TokensRegistry;
use App\Repository\TokensRegistryRepository;
use App\Tests\Repository\RepositoryTrait\FinderTrait;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

class InMemoryTokensRegistryRepository extends TokensRegistryRepository
{
    use FinderTrait;

    /**
     * @var ArrayCollection
     */
    private ArrayCollection $entities;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }

    /**
     * @param TokensRegistry $entity
     * @param bool $flush
     * @return void
     */
    public function save(TokensRegistry $entity, bool $flush = false): void
    {
        if ($flush) {
            $entityId = spl_object_id($entity);
            $this->entities->set($entityId, $entity);
        }
    }

    public function findIfValidToken(string $tokenType, string $token, bool $expiration = false): ?TokensRegistry
    {
        /** @var TokensRegistry $result */
        $result = $this->findOneBy([$tokenType => $token]);

        if ($expiration) {
            if (new DateTime() > $result->getResetPassExpiration()) {
                return null;
            }
        }

        return $result;
    }
}