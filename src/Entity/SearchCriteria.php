<?php
// src/Entity/SearchCriteria.php

namespace App\Entity;

use App\Repository\SearchCriteriaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SearchCriteriaRepository::class)]
#[ORM\Table(name: 'search_criterias')]
#[ORM\HasLifecycleCallbacks]
class SearchCriteria
{
    const TYPE_TEXT = 'text';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_CHOICE = 'choice';
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';

    const OPERATOR_EQUALS = 'equals';
    const OPERATOR_NOT_EQUALS = 'not_equals';
    const OPERATOR_CONTAINS = 'contains';
    const OPERATOR_STARTS_WITH = 'starts_with';
    const OPERATOR_ENDS_WITH = 'ends_with';
    const OPERATOR_GREATER_THAN = 'greater_than';
    const OPERATOR_LESS_THAN = 'less_than';
    const OPERATOR_BETWEEN = 'between';
    const OPERATOR_IN = 'in';
    const OPERATOR_NOT_IN = 'not_in';
    const OPERATOR_IS_NULL = 'is_null';
    const OPERATOR_IS_NOT_NULL = 'is_not_null';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $fieldName = null;

    #[ORM\Column(length: 50)]
    private ?string $type = self::TYPE_TEXT;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $operators = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $options = [];

    #[ORM\Column]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\Column]
    private ?bool $isRequired = false;

    #[ORM\ManyToOne(targetEntity: CriteriaGroup::class, inversedBy: 'searchCriterias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CriteriaGroup $criteriaGroup = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->operators = [];
        $this->options = [];
    }

    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): static
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getOperators(): array
    {
        return $this->operators;
    }

    public function setOperators(?array $operators): static
    {
        $this->operators = $operators ?? [];
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(?array $options): static
    {
        $this->options = $options ?? [];
        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function isIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): static
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function getCriteriaGroup(): ?CriteriaGroup
    {
        return $this->criteriaGroup;
    }

    public function setCriteriaGroup(?CriteriaGroup $criteriaGroup): static
    {
        $this->criteriaGroup = $criteriaGroup;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // Méthodes utilitaires
    public static function getAvailableTypes(): array
    {
        return [
            'Texte' => self::TYPE_TEXT,
            'Nombre entier' => self::TYPE_INTEGER,
            'Nombre décimal' => self::TYPE_FLOAT,
            'Booléen' => self::TYPE_BOOLEAN,
            'Date' => self::TYPE_DATE,
            'Date et heure' => self::TYPE_DATETIME,
            'Choix unique' => self::TYPE_CHOICE,
            'Choix multiple' => self::TYPE_MULTIPLE_CHOICE,
        ];
    }

    public static function getAvailableOperators(): array
    {
        return [
            'Égal à' => self::OPERATOR_EQUALS,
            'Différent de' => self::OPERATOR_NOT_EQUALS,
            'Contient' => self::OPERATOR_CONTAINS,
            'Commence par' => self::OPERATOR_STARTS_WITH,
            'Termine par' => self::OPERATOR_ENDS_WITH,
            'Supérieur à' => self::OPERATOR_GREATER_THAN,
            'Inférieur à' => self::OPERATOR_LESS_THAN,
            'Entre' => self::OPERATOR_BETWEEN,
            'Dans la liste' => self::OPERATOR_IN,
            'Pas dans la liste' => self::OPERATOR_NOT_IN,
            'Est vide' => self::OPERATOR_IS_NULL,
            "N\'est pas vide" => self::OPERATOR_IS_NOT_NULL,
        ];
    }

    public function getTypeSpecificOperators(): array
    {
        $baseOperators = [
            self::OPERATOR_EQUALS,
            self::OPERATOR_NOT_EQUALS,
            self::OPERATOR_IS_NULL,
            self::OPERATOR_IS_NOT_NULL,
        ];

        $typeSpecific = match($this->type) {
            self::TYPE_TEXT => [
                self::OPERATOR_CONTAINS,
                self::OPERATOR_STARTS_WITH,
                self::OPERATOR_ENDS_WITH,
            ],
            self::TYPE_INTEGER, self::TYPE_FLOAT => [
                self::OPERATOR_GREATER_THAN,
                self::OPERATOR_LESS_THAN,
                self::OPERATOR_BETWEEN,
            ],
            self::TYPE_BOOLEAN => [
                // Opérateurs spécifiques pour les booléens
            ],
            self::TYPE_DATE, self::TYPE_DATETIME => [
                self::OPERATOR_GREATER_THAN,
                self::OPERATOR_LESS_THAN,
                self::OPERATOR_BETWEEN,
            ],
            self::TYPE_CHOICE, self::TYPE_MULTIPLE_CHOICE => [
                self::OPERATOR_IN,
                self::OPERATOR_NOT_IN,
            ],
            default => [],
        };

        return array_merge($baseOperators, $typeSpecific);
    }

    public function getFormType(): string
    {
        return match($this->type) {
            self::TYPE_TEXT => 'text',
            self::TYPE_INTEGER => 'integer',
            self::TYPE_FLOAT => 'number',
            self::TYPE_BOOLEAN => 'checkbox',
            self::TYPE_DATE => 'date',
            self::TYPE_DATETIME => 'datetime',
            self::TYPE_CHOICE => 'radio',
            self::TYPE_MULTIPLE_CHOICE => 'checkbox',
            default => 'text',
        };
    }

    public function getFormOptions(): array
    {
        $options = [
            'label' => $this->name,
            'required' => $this->isRequired,
            'attr' => [
                'data-criteria-type' => $this->type,
                'data-criteria-operators' => json_encode($this->operators),
            ],
        ];

        // Options spécifiques selon le type
        switch ($this->type) {
            case self::TYPE_TEXT:
                $options['attr']['placeholder'] = 'Entrez votre recherche...';
                break;

            case self::TYPE_INTEGER:
                $options['attr'] = array_merge($options['attr'], [
                    'placeholder' => 'Entrez un nombre entier...',
                    'min' => $this->options['min'] ?? null,
                    'max' => $this->options['max'] ?? null,
                ]);
                break;

            case self::TYPE_FLOAT:
                $options['scale'] = 2;
                $options['attr'] = array_merge($options['attr'], [
                    'placeholder' => 'Entrez un nombre décimal...',
                    'step' => '0.01',
                    'min' => $this->options['min'] ?? null,
                    'max' => $this->options['max'] ?? null,
                ]);
                break;

            case self::TYPE_BOOLEAN:
                $options['required'] = false;
                $options['false_values'] = [false, null, '0', ''];
                break;

            case self::TYPE_CHOICE:
                $options['choices'] = $this->options['choices'] ?? [];
                $options['placeholder'] = 'Choisissez une option...';
                $options['multiple'] = false;
                $options['expanded'] = false;
                break;

            case self::TYPE_MULTIPLE_CHOICE:
                $options['choices'] = $this->options['choices'] ?? [];
                $options['multiple'] = true;
                $options['expanded'] = false;
                $options['attr']['class'] = 'select2-multiple';
                break;

            case self::TYPE_DATE:
            case self::TYPE_DATETIME:
                $options['widget'] = 'single_text';
                $options['html5'] = true;
                $options['attr']['class'] = 'datepicker';
                break;
        }

        return $options;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}