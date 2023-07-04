<?php

namespace App\Repository;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class RescueRepository extends ServiceEntityRepository
{
    public function findWhereActive(?array $orderBy = null, $limit = null, $offset = null)
    {
        $queryBuilder = $this->getDefaultQueryBuilder();

        if ($orderBy) {
            foreach ($orderBy as $sort => $order) {
                $queryBuilder->orderBy($sort, $order);
            }
        }

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        if ($offset) {
            $queryBuilder->setFirstResult($offset);
        }

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countWhereActive()
    {
        return $this->getDefaultQueryBuilder()->select('count(r.id)')->getQuery()->getSingleScalarResult();
    }

    protected function getDefaultQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->where('r.endDate > :date')
            ->setParameter('date', new DateTime());
    }
}
