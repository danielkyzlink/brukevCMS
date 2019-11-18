<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    const STATE_SMAZANO = 0;
    const STATE_PUBLIKOVANO = 1;
    const STATE_REVIZE = 2;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     */
    private $category;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $active;    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $perex;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateOfCreated;
    
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $seoTitle;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private $state;
    
    public function __construct()
    {
        $this->setState(self::STATE_REVIZE);
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getActive(): ?bool
    {
        return $this->active;
    }
    
    public function setActive(?bool $active): self
    {
        $this->active = $active;
        
        return $this;
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
    
    public function getPicture(): ?string
    {
        return $this->picture;
    }
    
    public function setPicture(string $picture): self
    {
        $this->picture = $picture;
        
        return $this;
    }

    public function getPerex(): ?string
    {
        return $this->perex;
    }

    public function setPerex(?string $perex): self
    {
        $this->perex = $perex;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }
    
    public function getDateOfCreated(): ?object
    {
        return $this->dateOfCreated;
    }
    
    public function setDateOfCreated(?object $dateOfCreated): self
    {
        $this->dateOfCreated = $dateOfCreated;
        
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
    
    public function getState(): int
    {
        return $this->state;
    }
    
    public function setState(int $state): self
    {
        $this->state = $state;
        
        return $this;
    }
    
    public function getCategory(): ?Category
    {
        return $this->category;
    }
    
    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        
        return $this;
    }
}
