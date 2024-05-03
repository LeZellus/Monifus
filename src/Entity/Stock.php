<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $quantity = null;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: Sale::class)]
    private $sales; // Cette ligne est ajoutée

    public function __construct()
    {
        $this->sales = new ArrayCollection(); // Cela permet d'initialiser la collection des ventes
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function __toString(): string
    {
        return (string) $this->quantity;
    }
}
