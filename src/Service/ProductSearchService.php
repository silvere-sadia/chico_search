<?php
// src/Service/ProductSearchService.php

namespace App\Service;

use App\DTO\ProductSearchCriteria;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductSearchService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function handleSearchRequest(Request $request): array
    {
        $searchCriteria = new ProductSearchCriteria();
        
        // Récupération des paramètres de la requête
        $page = $request->query->getInt('page', 1);
        $searchCriteria->page = max(1, $page);
        
        // Application des autres critères depuis la requête
        $this->applyQueryParameters($searchCriteria, $request);
        
        return $this->productRepository->findBySearchCriteria($searchCriteria);
    }

    private function applyQueryParameters(ProductSearchCriteria $criteria, Request $request): void
    {
        $criteria->searchTerm = $request->query->get('searchTerm');
        $criteria->minPrice = $request->query->get('minPrice');
        $criteria->maxPrice = $request->query->get('maxPrice');
        $criteria->minQuantity = $request->query->get('minQuantity');
        $criteria->maxQuantity = $request->query->get('maxQuantity');
        $criteria->minWeight = $request->query->get('minWeight');
        $criteria->maxWeight = $request->query->get('maxWeight');
        $criteria->minRating = $request->query->get('minRating');
        $criteria->maxRating = $request->query->get('maxRating');
        $criteria->categories = $request->query->all('categories') ?? [];
        $criteria->tags = $request->query->all('tags') ?? [];
        $criteria->isActive = $request->query->get('isActive');
        $criteria->sortBy = $request->query->get('sortBy', 'name');
        $criteria->sortOrder = $request->query->get('sortOrder', 'ASC');
        
        // Gestion des dates
        if ($createdAfter = $request->query->get('createdAfter')) {
            $criteria->createdAfter = new \DateTime($createdAfter);
        }
        
        if ($createdBefore = $request->query->get('createdBefore')) {
            $criteria->createdBefore = new \DateTime($createdBefore);
        }
    }
}