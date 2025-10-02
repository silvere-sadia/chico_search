<?php
// src/Repository/CriteriaGroupRepository.php

namespace App\Repository;

use App\Entity\CriteriaGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CriteriaGroup>
 */
class CriteriaGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CriteriaGroup::class);
    }

    public function findActiveGroupsWithCriteria(): array
    {
        return $this->createQueryBuilder('cg')
            ->leftJoin('cg.searchCriterias', 'sc')
            ->addSelect('sc')
            ->where('cg.isActive = :isActive')
            ->andWhere('sc.isActive = :criteriaActive')
            ->setParameter('isActive', true)
            ->setParameter('criteriaActive', true)
            ->orderBy('cg.sortOrder', 'ASC')
            ->addOrderBy('sc.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCode(string $code): ?CriteriaGroup
    {
        return $this->createQueryBuilder('cg')
            ->where('cg.code = :code')
            ->andWhere('cg.isActive = :isActive')
            ->setParameter('code', $code)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}