<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{

    const SELECT_MODE_SINGLE = 'single_select';
    const SELECT_MODE_MULTIPLE = 'multiple_select';

    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_TEXT = 'text';
    const TYPE_LONG_TEXT = 'textarea';
    const TYPE_RANGE = 'range';
    const TYPE_BOOLEAN = 'boolean';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $position = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $childsLimit = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, Criteria>
     */
    #[ORM\OneToMany(targetEntity: Criteria::class, mappedBy: 'parent', cascade: ['persist', 'remove', 'refresh'])]
    private Collection $criterias;

    public function __construct()
    {
        $this->criterias = new ArrayCollection();
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getChildsLimit(): ?int
    {
        return $this->childsLimit;
    }

    public function setChildsLimit(?int $childsLimit): static
    {
        $this->childsLimit = $childsLimit;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, Criteria>
     */
    public function getCriterias(): Collection
    {
        return $this->criterias;
    }

    public function addCriteria(Criteria $criteria): static
    {
        if (!$this->criterias->contains($criteria)) {
            $this->criterias->add($criteria);
            $criteria->setParent($this);
        }

        return $this;
    }

    public function removeCriteria(Criteria $criteria): static
    {
        if ($this->criterias->removeElement($criteria)) {
            // set the owning side to null (unless already changed)
            if ($criteria->getParent() === $this) {
                $criteria->setParent(null);
            }
        }

        return $this;
    }
}
