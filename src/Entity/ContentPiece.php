<?php

namespace App\Entity;

use App\Repository\ContentPieceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentPieceRepository::class)]
class ContentPiece
{
    const STATE_SMAZANO = 0;
    const STATE_PUBLIKOVANO = 1;
    const TYPE_TEXTAREA = 1;
    const TYPE_TEXT = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $state = null;

    #[ORM\Column(nullable: true)]
    private ?bool $plain = null;

    #[ORM\Column(nullable: true)]
    private ?int $type = null;

    #[ORM\Column(nullable: true)]
    private ?bool $adminEditable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $section = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function isPlain(): ?bool
    {
        return $this->plain;
    }

    public function setPlain(?bool $plain): self
    {
        $this->plain = $plain;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isAdminEditable(): ?bool
    {
        return $this->adminEditable;
    }

    public function setAdminEditable(?bool $adminEditable): self
    {
        $this->adminEditable = $adminEditable;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): self
    {
        $this->section = $section;

        return $this;
    }
}
