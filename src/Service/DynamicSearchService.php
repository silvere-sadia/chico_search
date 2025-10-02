<?php
// src/Service/DynamicSearchService.php

namespace App\Service;

use App\Entity\CriteriaGroup;
use App\Entity\SearchCriteria;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use App\Repository\CriteriaGroupRepository;
use App\Repository\SearchCriteriaRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DynamicSearchService
{
    const TYPE_TEXT_PATH = 'Symfony\Component\Form\Extension\Core\Type\TextType';
    const TYPE_INTEGER_PATH = 'Symfony\Component\Form\Extension\Core\Type\IntegerType';
    const TYPE_FLOAT_PATH = 'Symfony\Component\Form\Extension\Core\Type\NumberType';
    const TYPE_BOOLEAN_PATH = 'Symfony\Component\Form\Extension\Core\Type\CheckboxType';
    const TYPE_DATE_PATH = 'Symfony\Component\Form\Extension\Core\Type\DateType';
    const TYPE_DATETIME_PATH = 'Symfony\Component\Form\Extension\Core\Type\DateTimeType';
    const TYPE_CHOICE_PATH = 'Symfony\Component\Form\Extension\Core\Type\ChoiceType';
    const TYPE_MULTIPLE_CHOICE_PATH = 'Symfony\Component\Form\Extension\Core\Type\CheckboxType';


    public function __construct(
        private CriteriaGroupRepository $criteriaGroupRepository,
        private SearchCriteriaRepository $searchCriteriaRepository,
        private FormFactoryInterface $formFactory
    ) {}

    /**
     * Crée un formulaire de recherche dynamique basé sur les groupes de critères
     */
    public function createSearchForm(string $formTypeClass, array $groups = []): FormInterface
    {
        $activeGroups = empty($groups) 
            ? $this->criteriaGroupRepository->findActiveGroupsWithCriteria()
            : $this->criteriaGroupRepository->findBy(['code' => $groups, 'isActive' => true]);
        
        $formBuilder = $this->formFactory->createBuilder($formTypeClass);
        
        foreach ($activeGroups as $group) {
            $this->addGroupToForm($formBuilder, $group);
        }
        
        // Ajouter les boutons d'action
        $formBuilder->add('search', SubmitType::class, [
            'label' => 'Rechercher',
            'attr' => ['class' => 'btn btn-primary']
            ])->add('reset', SubmitType::class, [
                'label' => 'Réinitialiser',
                'attr' => ['class' => 'btn btn-secondary']
            ])
        ;
            
        $form = $formBuilder->getForm();
        // dd($formTypeClass, $activeGroups, $formBuilder, $form);
        return $form;
    }

    /**
     * Ajoute un groupe de critères au formulaire
     */
    private function addGroupToForm(FormBuilderInterface $formBuilder, CriteriaGroup $group): void
    {
        $fieldName = $group->getCode();
        $options = $group->getFormOptions();


        // Ajouter les choix pour les modes de sélection
        if (in_array($group->getDisplayMode(), [
            CriteriaGroup::DISPLAY_SINGLE_SELECT,
            CriteriaGroup::DISPLAY_MULTIPLE_SELECT,
            CriteriaGroup::DISPLAY_RADIO,
            CriteriaGroup::DISPLAY_CHECKBOX
        ])) {
            $options['choices'] = $this->getGroupChoices($group);
        }


        // Configuration spécifique pour les plages
        if ($group->getDisplayMode() === CriteriaGroup::DISPLAY_RANGE) {
            $options = array_merge($options, $this->getRangeOptions($group));
        }

        $formBuilder->add($fieldName, self::getOneTypePath($group->getFormType()), $options);
    }

    /**
     * Récupère les choix pour un groupe
     */
    private function getGroupChoices(CriteriaGroup $group): array
    {
        $choices = [];
        foreach ($group->getSearchCriterias() as $criteria) {
            if ($criteria->isIsActive()) {
                $choices[$criteria->getName()] = $criteria->getFieldName();
            }
        }
        return $choices;
    }

    /**
     * Options spécifiques pour les plages de valeurs
     */
    private function getRangeOptions(CriteriaGroup $group): array
    {
        $min = null;
        $max = null;

        // Calculer les min/max basés sur les critères du groupe
        foreach ($group->getSearchCriterias() as $criteria) {
            if ($criteria->isIsActive()) {
                $criteriaOptions = $criteria->getOptions();
                if (isset($criteriaOptions['min'])) {
                    $min = min($min ?? $criteriaOptions['min'], $criteriaOptions['min']);
                }
                if (isset($criteriaOptions['max'])) {
                    $max = max($max ?? $criteriaOptions['max'], $criteriaOptions['max']);
                }
            }
        }

        return [
            'attr' => [
                'data-min' => $min ?? 0,
                'data-max' => $max ?? 100,
                'data-type' => 'range',
                'class' => 'range-slider'
            ]
        ];
    }

    /**
     * Applique les critères de recherche à une requête Doctrine
     */
    public function applySearchCriteria(QueryBuilder $queryBuilder, array $formData, string $entityAlias = 'entity'): void
    {
        foreach ($formData as $groupCode => $selectedCriteria) {
            if (empty($selectedCriteria)) {
                continue;
            }

            $group = $this->criteriaGroupRepository->findByCode($groupCode);
            if (!$group) {
                continue;
            }

            $this->applyGroupCriteriaToQuery($queryBuilder, $group, $selectedCriteria, $entityAlias);
        }
    }

    private function applyGroupCriteriaToQuery(QueryBuilder $qb, CriteriaGroup $group, $selectedCriteria, string $alias): void
    {
        $fieldNames = [];

        // Récupérer les noms de champs selon le mode de sélection
        if ($group->getDisplayMode() === CriteriaGroup::DISPLAY_RANGE) {
            // Pour les plages, on applique tous les critères du groupe
            foreach ($group->getSearchCriterias() as $criteria) {
                if ($criteria->isIsActive()) {
                    $fieldNames[] = $criteria->getFieldName();
                }
            }
        } elseif (is_array($selectedCriteria)) {
            // Pour les sélections multiples
            $fieldNames = $selectedCriteria;
        } else {
            // Pour les sélections uniques
            $fieldNames = [$selectedCriteria];
        }

        // Appliquer les critères sélectionnés
        foreach ($fieldNames as $fieldName) {
            $criteria = $this->searchCriteriaRepository->findByFieldName($fieldName);
            if ($criteria) {
                $this->applyCriterionToQuery($qb, $criteria, $alias);
            }
        }
    }

    private function applyCriterionToQuery(QueryBuilder $qb, SearchCriteria $criteria, string $alias): void
    {
        $field = $alias . '.' . $criteria->getFieldName();
        
        // Implémentation basique - à adapter selon les opérateurs
        $qb->andWhere($qb->expr()->isNotNull($field));
        
        // Ici vous pouvez implémenter la logique spécifique selon le type de critère
        // et les opérateurs configurés
    }

    /**
     * Génère un DTO de recherche basé sur les groupes
     */
    public function createSearchDto(array $formData): array
    {
        $searchDto = [];

        foreach ($formData as $groupCode => $value) {
            if (!empty($value)) {
                $searchDto[$groupCode] = $value;
            }
        }

        return $searchDto;
    }

    public static function getAllTypesPath(): array
    {
        return [
            SearchCriteria::TYPE_TEXT => self::TYPE_TEXT_PATH,
            SearchCriteria::TYPE_INTEGER => self::TYPE_INTEGER_PATH,
            SearchCriteria::TYPE_FLOAT => self::TYPE_FLOAT_PATH,
            SearchCriteria::TYPE_BOOLEAN => self::TYPE_BOOLEAN_PATH,
            SearchCriteria::TYPE_DATE => self::TYPE_DATE_PATH,
            SearchCriteria::TYPE_DATETIME => self::TYPE_DATETIME_PATH,
            SearchCriteria::TYPE_CHOICE => self::TYPE_CHOICE_PATH,
            SearchCriteria::TYPE_MULTIPLE_CHOICE => self::TYPE_MULTIPLE_CHOICE_PATH,
        ];
    }

    public static function getOneTypePath(string $type): string
    {
        $paths = self::getAllTypesPath();
        return $paths[$type];
    }

}