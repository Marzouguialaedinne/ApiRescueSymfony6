<?php

namespace App\Repository;

use App\Entity\TokensRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @extends ServiceEntityRepository<TokensRegistry>
 *
 * @method TokensRegistry|null find($id, $lockMode = null, $lockVersion = null)
 * @method TokensRegistry|null findOneBy(array $criteria, array $orderBy = null)
 * @method TokensRegistry[]    findAll()
 * @method TokensRegistry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokensRegistryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TokensRegistry::class);
    }

    public function save(TokensRegistry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TokensRegistry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findIfValidToken(string $tokenType, string $token, bool $expiration = false): ?TokensRegistry
    {
        if ($expiration) {
            return $this->createQueryBuilder('t')
                ->andWhere('t.' . $tokenType . ' = :token')
                ->andWhere('t.' . $tokenType . 'Expiration > :current')
                ->setParameter('token', $token)
                ->setParameter('current', new DateTime())
                ->getQuery()
                ->getOneOrNullResult()
                ;
        }

        return $this->createQueryBuilder('t')
            ->andWhere('t.' . $tokenType . ' = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
            ;


    }
}
