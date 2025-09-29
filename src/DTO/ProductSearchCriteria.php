<?php
// src/DTO/ProductSearchCriteria.php

namespace App\DTO;

class ProductSearchCriteria
{
    public ?string $searchTerm = null;
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?int $minQuantity = null;
    public ?int $maxQuantity = null;
    public ?float $minWeight = null;
    public ?float $maxWeight = null;
    public ?float $minRating = null;
    public ?float $maxRating = null;
    
    /** @var string[] */
    public array $categories = [];
    
    /** @var string[] */
    public array $tags = [];
    
    public ?bool $isActive = null;
    public ?\DateTimeInterface $createdAfter = null;
    public ?\DateTimeInterface $createdBefore = null;
    
    public string $sortBy = 'name';
    public string $sortOrder = 'ASC';
    public int $page = 1;
    public int $limit = 20;

    public function hasPriceRange(): bool
    {
        return $this->minPrice !== null || $this->maxPrice !== null;
    }

    public function hasQuantityRange(): bool
    {
        return $this->minQuantity !== null || $this->maxQuantity !== null;
    }

    public function hasWeightRange(): bool
    {
        return $this->minWeight !== null || $this->maxWeight !== null;
    }

    public function hasRatingRange(): bool
    {
        return $this->minRating !== null || $this->maxRating !== null;
    }

    public function hasDateRange(): bool
    {
        return $this->createdAfter !== null || $this->createdBefore !== null;
    }
}