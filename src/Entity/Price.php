<?php

namespace App\Entity;

use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PriceRepository::class)]
class Price
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: "Le prix doit être un nombre positif ou zéro.")]
    #[Assert\Type(
        type: "integer",
        message: "Le prix doit être un nombre entier."
    )]
    private ?int $priceOne = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: "Le prix doit être un nombre positif ou zéro.")]
    #[Assert\Type(
        type: "integer",
        message: "Le prix doit être un nombre entier."
    )]
    private ?int $priceTen = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: "Le prix doit être un nombre positif ou zéro.")]
    #[Assert\Type(
        type: "integer",
        message: "Le prix doit être un nombre entier."
    )]
    private ?int $priceHundred = null;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?monitor $monitor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceOne(): ?int
    {
        return $this->priceOne;
    }

    public function setPriceOne(?int $priceOne): static
    {
        $this->priceOne = $priceOne;

        return $this;
    }

    public function getPriceTen(): ?int
    {
        return $this->priceTen;
    }

    public function setPriceTen(?int $priceTen): static
    {
        $this->priceTen = $priceTen;

        return $this;
    }

    public function getPriceHundred(): ?int
    {
        return $this->priceHundred;
    }

    public function setPriceHundred(?int $priceHundred): static
    {
        $this->priceHundred = $priceHundred;

        return $this;
    }

    public function getMonitor(): ?monitor
    {
        return $this->monitor;
    }

    public function setMonitor(?monitor $monitor): static
    {
        $this->monitor = $monitor;

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
}
