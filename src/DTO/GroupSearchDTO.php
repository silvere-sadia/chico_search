<?php
// src/DTO/GroupedSearchDTO.php

namespace App\DTO;

class GroupedSearchDTO
{
    private array $criteria = [];

    public function addCriteria(string $groupCode, $value): void
    {
        $this->criteria[$groupCode] = $value;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function getGroupCriteria(string $groupCode)
    {
        return $this->criteria[$groupCode] ?? null;
    }

    public function hasCriteria(): bool
    {
        return !empty($this->criteria);
    }

    public function hasGroupCriteria(string $groupCode): bool
    {
        return isset($this->criteria[$groupCode]) && !empty($this->criteria[$groupCode]);
    }
}