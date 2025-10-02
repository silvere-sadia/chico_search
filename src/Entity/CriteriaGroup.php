<?php
// src/Entity/CriteriaGroup.php

namespace App\Entity;

use App\Repository\CriteriaGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CriteriaGroupRepository::class)]
#[ORM\Table(name: 'criteria_groups')]
class CriteriaGroup
{
    const DISPLAY_SINGLE_SELECT = 'single_select';
    const DISPLAY_MULTIPLE_SELECT = 'multiple_select';
    const DISPLAY_RADIO = 'radio';
    const DISPLAY_CHECKBOX = 'checkbox';
    const DISPLAY_INPUT = 'input';
    const DISPLAY_RANGE = 'range';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 20)]
    private ?string $displayMode = self::DISPLAY_CHECKBOX;

    #[ORM\Column]
    private ?int $sortOrder = 0;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\Column]
    private ?bool $isMultiple = false;

    #[ORM\Column(nullable: true)]
    private ?int $maxSelections = null;

    #[ORM\OneToMany(targetEntity: SearchCriteria::class, mappedBy: 'criteriaGroup', cascade: ['persist', 'remove'])]
    private Collection $searchCriterias;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->searchCriterias = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getDisplayMode(): ?string
    {
        return $this->displayMode;
    }

    public function setDisplayMode(string $displayMode): static
    {
        $this->displayMode = $displayMode;
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

    public function isIsMultiple(): ?bool
    {
        return $this->isMultiple;
    }

    public function setIsMultiple(bool $isMultiple): static
    {
        $this->isMultiple = $isMultiple;
        return $this;
    }

    public function getMaxSelections(): ?int
    {
        return $this->maxSelections;
    }

    public function setMaxSelections(?int $maxSelections): static
    {
        $this->maxSelections = $maxSelections;
        return $this;
    }

    /**
     * @return Collection<int, SearchCriteria>
     */
    public function getSearchCriterias(): Collection
    {
        return $this->searchCriterias;
    }

    public function addSearchCriteria(SearchCriteria $searchCriteria): static
    {
        if (!$this->searchCriterias->contains($searchCriteria)) {
            $this->searchCriterias->add($searchCriteria);
            $searchCriteria->setCriteriaGroup($this);
        }

        return $this;
    }

    public function removeSearchCriteria(SearchCriteria $searchCriteria): static
    {
        if ($this->searchCriterias->removeElement($searchCriteria)) {
            // set the owning side to null (unless already changed)
            if ($searchCriteria->getCriteriaGroup() === $this) {
                $searchCriteria->setCriteriaGroup(null);
            }
        }

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

    public static function getAvailableDisplayModes(): array
    {
        return [
            'Sélection unique (dropdown)' => self::DISPLAY_SINGLE_SELECT,
            'Sélection multiple (dropdown)' => self::DISPLAY_MULTIPLE_SELECT,
            'Boutons radio' => self::DISPLAY_RADIO,
            'Cases à cocher' => self::DISPLAY_CHECKBOX,
            'Champ de saisie' => self::DISPLAY_INPUT,
            'Plage de valeurs' => self::DISPLAY_RANGE,
        ];
    }

    public function getFormType(): string
    {
        return match($this->displayMode) {
            self::DISPLAY_SINGLE_SELECT => 'choice',
            self::DISPLAY_MULTIPLE_SELECT => 'choice',
            self::DISPLAY_RADIO => 'choice',
            self::DISPLAY_CHECKBOX => 'choice',
            self::DISPLAY_INPUT => 'text',
            self::DISPLAY_RANGE => 'range',
            default => 'choice',
        };
    }

    public function getFormOptions(): array
    {
        $options = [
            'label' => $this->name,
            'required' => false,
        ];

        switch ($this->displayMode) {
            case self::DISPLAY_SINGLE_SELECT:
                $options['multiple'] = false;
                $options['expanded'] = false;
                $options['placeholder'] = 'Sélectionnez...';
                $options['attr'] = ['class' => 'select2-single'];
                break;

            case self::DISPLAY_MULTIPLE_SELECT:
                $options['multiple'] = true;
                $options['expanded'] = false;
                $options['attr'] = ['class' => 'select2-multiple'];
                break;

            case self::DISPLAY_RADIO:
                $options['multiple'] = false;
                $options['expanded'] = true;
                $options['placeholder'] = false;
                break;

            case self::DISPLAY_CHECKBOX:
                $options['multiple'] = true;
                $options['expanded'] = true;
                break;

            case self::DISPLAY_INPUT:
                $options['attr'] = ['placeholder' => 'Entrez votre recherche...'];
                break;

            case self::DISPLAY_RANGE:
                $options['attr'] = [
                    'data-type' => 'range',
                    'class' => 'range-slider'
                ];
                break;
        }

        if ($this->maxSelections) {
            $options['attr']['data-max-selections'] = $this->maxSelections;
        }

        return $options;
    }

    public function getCriteriaChoices(): array
    {
        $choices = [];
        foreach ($this->searchCriterias as $criteria) {
            if ($criteria->isIsActive()) {
                $choices[$criteria->getName()] = $criteria->getFieldName();
            }
        }
        return $choices;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}