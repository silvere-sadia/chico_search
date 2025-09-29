<?php
// src/Repository/ProductRepository.php

namespace App\Repository;

use App\Entity\Product;
use App\DTO\ProductSearchCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findBySearchCriteria(ProductSearchCriteria $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('p');
        
        $this->applySearchTerm($queryBuilder, $criteria->searchTerm);
        $this->applyPriceRange($queryBuilder, $criteria->minPrice, $criteria->maxPrice);
        $this->applyQuantityRange($queryBuilder, $criteria->minQuantity, $criteria->maxQuantity);
        $this->applyWeightRange($queryBuilder, $criteria->minWeight, $criteria->maxWeight);
        $this->applyRatingRange($queryBuilder, $criteria->minRating, $criteria->maxRating);
        $this->applyCategories($queryBuilder, $criteria->categories);
        $this->applyTags($queryBuilder, $criteria->tags);
        $this->applyActiveStatus($queryBuilder, $criteria->isActive);
        $this->applyDateRange($queryBuilder, $criteria->createdAfter, $criteria->createdBefore);
        $this->applySorting($queryBuilder, $criteria->sortBy, $criteria->sortOrder);

        // Pagination
        $queryBuilder
            ->setFirstResult(($criteria->page - 1) * $criteria->limit)
            ->setMaxResults($criteria->limit);

        $query = $queryBuilder->getQuery();
        
        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $criteria->limit);

        return [
            'products' => $paginator->getIterator(),
            'totalItems' => $totalItems,
            'pagesCount' => $pagesCount,
            'currentPage' => $criteria->page,
        ];
    }

    private function applySearchTerm(QueryBuilder $qb, ?string $searchTerm): void
    {
        if (!$searchTerm) {
            return;
        }

        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->like('p.name', ':searchTerm'),
            $qb->expr()->like('p.description', ':searchTerm')
        ))
        ->setParameter('searchTerm', '%' . $searchTerm . '%');
    }

    private function applyPriceRange(QueryBuilder $qb, ?float $minPrice, ?float $maxPrice): void
    {
        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }
    }

    private function applyQuantityRange(QueryBuilder $qb, ?int $minQuantity, ?int $maxQuantity): void
    {
        if ($minQuantity !== null) {
            $qb->andWhere('p.quantity >= :minQuantity')
               ->setParameter('minQuantity', $minQuantity);
        }

        if ($maxQuantity !== null) {
            $qb->andWhere('p.quantity <= :maxQuantity')
               ->setParameter('maxQuantity', $maxQuantity);
        }
    }

    private function applyWeightRange(QueryBuilder $qb, ?float $minWeight, ?float $maxWeight): void
    {
        if ($minWeight !== null) {
            $qb->andWhere('p.weight >= :minWeight')
               ->setParameter('minWeight', $minWeight);
        }

        if ($maxWeight !== null) {
            $qb->andWhere('p.weight <= :maxWeight')
               ->setParameter('maxWeight', $maxWeight);
        }
    }

    private function applyRatingRange(QueryBuilder $qb, ?float $minRating, ?float $maxRating): void
    {
        if ($minRating !== null) {
            $qb->andWhere('p.rating >= :minRating')
               ->setParameter('minRating', $minRating);
        }

        if ($maxRating !== null) {
            $qb->andWhere('p.rating <= :maxRating')
               ->setParameter('maxRating', $maxRating);
        }
    }

    private function applyCategories(QueryBuilder $qb, array $categories): void
    {
        if (empty($categories)) {
            return;
        }

        $qb->andWhere('p.category IN (:categories)')
           ->setParameter('categories', $categories);
    }

    private function applyTags(QueryBuilder $qb, array $tags): void
    {
        if (empty($tags)) {
            return;
        }

        foreach ($tags as $index => $tag) {
            $qb->andWhere("JSON_CONTAINS(p.tags, :tag{$index}) = 1")
               ->setParameter("tag{$index}", json_encode($tag));
        }
    }

    private function applyActiveStatus(QueryBuilder $qb, ?bool $isActive): void
    {
        if ($isActive !== null) {
            $qb->andWhere('p.isActive = :isActive')
               ->setParameter('isActive', $isActive);
        }
    }

    private function applyDateRange(QueryBuilder $qb, ?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate): void
    {
        if ($startDate !== null) {
            $qb->andWhere('p.createdAt >= :startDate')
               ->setParameter('startDate', $startDate);
        }

        if ($endDate !== null) {
            $qb->andWhere('p.createdAt <= :endDate')
               ->setParameter('endDate', $endDate);
        }
    }

    private function applySorting(QueryBuilder $qb, string $sortBy, string $sortOrder): void
    {
        $allowedSortFields = ['name', 'price', 'quantity', 'rating', 'createdAt'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

        $qb->orderBy('p.' . $sortBy, $sortOrder);
    }

    /**
     * Récupère les valeurs distinctes pour les filtres
     */
    public function getFilterValues(): array
    {
        $categories = $this->createQueryBuilder('p')
            ->select('DISTINCT p.category')
            ->orderBy('p.category', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();

        // $tags = $this->createQueryBuilder('p')
        //     ->select('DISTINCT tag')
        //     ->from('JSON_TABLE(p.tags, \'$[*]\' COLUMNS (tag VARCHAR(255) PATH \'$\')) AS tag')
        //     ->getQuery()
        //     ->getSingleColumnResult();

        return [
            'categories' => $categories,
            // 'tags' => array_unique($tags),
        ];
    }
}