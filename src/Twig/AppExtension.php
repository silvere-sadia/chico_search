<?php
// src/Twig/AppExtension.php

namespace App\Twig;

use App\Entity\SearchCriteria;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // Vos filtres existants...
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_criteria_icon', [$this, 'getCriteriaIcon']),
            new TwigFunction('get_criteria_type_label', [$this, 'getCriteriaTypeLabel']),
        ];
    }

    public function getCriteriaIcon(string $type): string
    {
        return match($type) {
            SearchCriteria::TYPE_TEXT => 'font',
            SearchCriteria::TYPE_INTEGER => 'hashtag',
            SearchCriteria::TYPE_FLOAT => 'divide',
            SearchCriteria::TYPE_BOOLEAN => 'toggle-on',
            SearchCriteria::TYPE_DATE => 'calendar',
            SearchCriteria::TYPE_DATETIME => 'clock',
            SearchCriteria::TYPE_CHOICE => 'list-ul',
            SearchCriteria::TYPE_MULTIPLE_CHOICE => 'tasks',
            default => 'question'
        };
    }

    public function getCriteriaTypeLabel(string $type): string
    {
        $types = SearchCriteria::getAvailableTypes();
        return array_search($type, $types) ?: $type;
    }
}