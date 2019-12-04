<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category")
     */
    private $articles;    
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $parent_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank;
    
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $seoTitle;
    
    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->setSeoTitle("");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(int $parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }
    
    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }
    
    public function setSeoTitle(string $seoTitle): self
    {
        $this->seoTitle = $seoTitle;
        
        return $this;
    }
    
    /**
     * @return Collection|Article[]
     */
    public function getProducts(): Collection
    {
        return $this->articles;
    }
}
