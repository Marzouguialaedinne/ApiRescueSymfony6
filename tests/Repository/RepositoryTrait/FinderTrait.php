<?php

namespace App\Tests\Repository\RepositoryTrait;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

trait FinderTrait
{
    /**
     * @var ArrayCollection
     */
    private ArrayCollection $entities;

    /**
     * @param array $criteriaArray
     * @return Criteria
     */
    private function toCriteria(array $criteriaArray): Criteria
    {
        $expr = Criteria::expr();
        $expressions = [];
        foreach ($criteriaArray as $key => $value) {
            $expressions[] = $expr->eq($key, $value);
        }

        return Criteria::create()->where($expr->andX(...$expressions));
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->entities->getValues();
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return object|false|mixed|null
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        $matches = $this->entities->matching(self::toCriteria($criteria));

        return $matches->first() ?: null;
    }
}
