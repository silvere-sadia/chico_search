<?php
// src/Repository/SearchCriteriaRepository.php

namespace App\Repository;

use App\Entity\SearchCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchCriteria>
 */
class SearchCriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchCriteria::class);
    }

    public function findActiveByGroup($groupId): array
    {
        return $this->createQueryBuilder('sc')
            ->where('sc.criteriaGroup = :groupId')
            ->andWhere('sc.isActive = :isActive')
            ->setParameter('groupId', $groupId)
            ->setParameter('isActive', true)
            ->orderBy('sc.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByFieldName(string $fieldName): ?SearchCriteria
    {
        return $this->createQueryBuilder('sc')
            ->where('sc.fieldName = :fieldName')
            ->andWhere('sc.isActive = :isActive')
            ->setParameter('fieldName', $fieldName)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActiveCriteria(): array
    {
        return $this->createQueryBuilder('sc')
            ->join('sc.criteriaGroup', 'cg')
            ->where('sc.isActive = :isActive')
            ->andWhere('cg.isActive = :groupActive')
            ->setParameter('isActive', true)
            ->setParameter('groupActive', true)
            ->orderBy('cg.sortOrder', 'ASC')
            ->addOrderBy('sc.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}