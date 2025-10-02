<?php
// src/DataFixtures/CriteriaFixtures.php

namespace App\DataFixtures;

use App\Entity\CriteriaGroup;
use App\Entity\SearchCriteria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CriteriaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Groupe 1: Filtres produits (Checkbox)
        $productGroup = new CriteriaGroup();
        $productGroup->setName('Filtres Produits');
        $productGroup->setCode('product_filters');
        $productGroup->setDescription('Critères de filtrage pour les produits');
        $productGroup->setDisplayMode(CriteriaGroup::DISPLAY_CHECKBOX);
        $productGroup->setIsMultiple(true);
        $productGroup->setSortOrder(1);

        $manager->persist($productGroup);

        // Critères pour le groupe produits
        $criteria1 = new SearchCriteria();
        $criteria1->setName('En stock');
        $criteria1->setFieldName('inStock');
        $criteria1->setType(SearchCriteria::TYPE_BOOLEAN);
        $criteria1->setCriteriaGroup($productGroup);
        $criteria1->setSortOrder(1);

        $criteria2 = new SearchCriteria();
        $criteria2->setName('Produit phare');
        $criteria2->setFieldName('isFeatured');
        $criteria2->setType(SearchCriteria::TYPE_BOOLEAN);
        $criteria2->setCriteriaGroup($productGroup);
        $criteria2->setSortOrder(2);

        // Groupe 2: Catégories (Select multiple)
        $categoryGroup = new CriteriaGroup();
        $categoryGroup->setName('Catégories');
        $categoryGroup->setCode('categories');
        $categoryGroup->setDescription('Sélection des catégories de produits');
        $categoryGroup->setDisplayMode(CriteriaGroup::DISPLAY_MULTIPLE_SELECT);
        $categoryGroup->setIsMultiple(true);
        $categoryGroup->setMaxSelections(3);
        $categoryGroup->setSortOrder(2);

        $manager->persist($categoryGroup);

        $criteria3 = new SearchCriteria();
        $criteria3->setName('Électronique');
        $criteria3->setFieldName('category_electronics');
        $criteria3->setType(SearchCriteria::TYPE_CHOICE);
        $criteria3->setCriteriaGroup($categoryGroup);
        $criteria3->setSortOrder(1);

        $criteria4 = new SearchCriteria();
        $criteria4->setName('Vêtements');
        $criteria4->setFieldName('category_clothing');
        $criteria4->setType(SearchCriteria::TYPE_CHOICE);
        $criteria4->setCriteriaGroup($categoryGroup);
        $criteria4->setSortOrder(2);

        // Groupe 3: Prix (Range)
        $priceGroup = new CriteriaGroup();
        $priceGroup->setName('Fourchette de prix');
        $priceGroup->setCode('price_range');
        $priceGroup->setDescription('Plage de prix des produits');
        $priceGroup->setDisplayMode(CriteriaGroup::DISPLAY_RANGE);
        $priceGroup->setSortOrder(3);

        $manager->persist($priceGroup);

        $criteria5 = new SearchCriteria();
        $criteria5->setName('Prix minimum');
        $criteria5->setFieldName('min_price');
        $criteria5->setType(SearchCriteria::TYPE_FLOAT);
        $criteria5->setOptions(['min' => 0, 'max' => 1000]);
        $criteria5->setCriteriaGroup($priceGroup);
        $criteria5->setSortOrder(1);

        $criteria6 = new SearchCriteria();
        $criteria6->setName('Prix maximum');
        $criteria6->setFieldName('max_price');
        $criteria6->setType(SearchCriteria::TYPE_FLOAT);
        $criteria6->setOptions(['min' => 0, 'max' => 1000]);
        $criteria6->setCriteriaGroup($priceGroup);
        $criteria6->setSortOrder(2);

        $manager->persist($criteria1);
        $manager->persist($criteria2);
        $manager->persist($criteria3);
        $manager->persist($criteria4);
        $manager->persist($criteria5);
        $manager->persist($criteria6);

        $manager->flush();
    }
}