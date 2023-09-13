<?php

namespace App\Entity;

use App\Repository\LostPasswordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LostPasswordRepository::class)]
class LostPassword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfRequest = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_renewed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getDateOfRequest(): ?\DateTimeInterface
    {
        return $this->dateOfRequest;
    }

    public function setDateOfRequest(?\DateTimeInterface $dateOfRequest): self
    {
        $this->dateOfRequest = $dateOfRequest;

        return $this;
    }

    public function getDateRenewed(): ?\DateTimeInterface
    {
        return $this->date_renewed;
    }

    public function setDateRenewed(?\DateTimeInterface $date_renewed): self
    {
        $this->date_renewed = $date_renewed;

        return $this;
    }
}
