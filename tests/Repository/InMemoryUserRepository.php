<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Repository\RepositoryTrait\FinderTrait;
use Doctrine\Common\Collections\ArrayCollection;

class InMemoryUserRepository extends UserRepository
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
     * @param User $entity
     * @param bool $flush
     * @return void
     */
    public function save(User $entity, bool $flush = false): void
    {
        if ($flush) {
            $entityId = spl_object_id($entity);
            $this->entities->set($entityId, $entity);
        }
    }
}