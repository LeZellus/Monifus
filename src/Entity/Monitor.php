<?php

namespace App\Entity;

use App\Repository\MonitorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonitorRepository::class)]

class Monitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'monitors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resource $resource = null;

    #[ORM\ManyToOne(inversedBy: 'monitors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'integer')]
    private mixed $pricePer1;

    #[ORM\Column(type: 'integer')]
    private mixed $pricePer10;

    #[ORM\Column(type: 'integer')]
    private mixed $pricePer100;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /*public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }*/
    public function getPricePer1(): mixed
    {
        return $this->pricePer1;
    }

    public function setPricePer1(mixed $pricePer1): void
    {
        $this->pricePer1 = $pricePer1;
    }

    public function getPricePer10(): mixed
    {
        return $this->pricePer10;
    }

    public function setPricePer10(mixed $pricePer10): void
    {
        $this->pricePer10 = $pricePer10;
    }

    public function getPricePer100(): mixed
    {
        return $this->pricePer100;
    }

    public function setPricePer100(mixed $pricePer100): void
    {
        $this->pricePer100 = $pricePer100;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): self
    {
        $this->resource = $resource;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
