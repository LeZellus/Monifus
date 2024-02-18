<?php

namespace App\Entity;

use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resource $Resource = null;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getResource(): ?Resource
    {
        return $this->Resource;
    }

    public function setResource(?Resource $Resource): static
    {
        $this->Resource = $Resource;

        return $this;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (empty($this->priceOne) && empty($this->priceTen) && empty($this->priceHundred)) {
            $context->buildViolation('Vous devez renseigner au moins un prix sur un des 3 lots.')
                ->addViolation();
        }
    }
}
