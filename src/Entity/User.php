<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\ValidImageExtension;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'L\'email du site ne peut pas être vide')]
    #[Assert\Email(message: 'Votre email n\'est pas valide')]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\Type("array")]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    # #[UniquePseudonyme]
    #[Assert\NotBlank(message: 'Le pseudonyme du site ne peut pas être vide')]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Minimum  {{ limit }} caractères',
        maxMessage: 'Maximum {{ limit }} caractères',
    )]
    private ?string $pseudonymeWebsite = null;

    #[ORM\Column(length: 255, nullable: true)]
    # #[UniquePseudonyme]
    #[Assert\NotBlank(message: 'Le pseudonyme du site ne peut pas être vide')]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Minimum  {{ limit }} caractères',
        maxMessage: 'Maximum {{ limit }} caractères',
    )]
    #[Assert\Type("string", message: 'Le pseudonyme ne doit contenir que des lettres')]
    private ?string $pseudonymeDofus = null;

    #[ORM\Column]
    #[Assert\Type("\DateTimeImmutable")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\Type("\DateTimeImmutable")]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\Type("bool")]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Record::class, orphanRemoval: true)]
    private Collection $records;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[Vich\UploadableField(mapping: 'user_profile', fileNameProperty: 'profilePicture')]
    #[ValidImageExtension]
    #[Assert\File(
        maxSize: "2M",
        mimeTypes: [
            "image/jpeg",
            "image/png",
            "image/webp",
            "image/gif",
            "image/svg+xml",
        ],
        mimeTypesMessage: 'Le fichier n\'est pas une image',
    )]
    private ?File $profilePictureFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverPicture = null;

    #[Vich\UploadableField(mapping: 'user_cover', fileNameProperty: 'coverPicture')]
    #[ValidImageExtension]
    #[Assert\File(
        maxSize: "2M",
        mimeTypes: [
            "image/jpeg",
            "image/png",
            "image/webp",
            "image/gif",
            "image/svg+xml",
        ],
        mimeTypesMessage: 'Le fichier n\'est pas une image',
    )]
    private ?File $coverPictureFile = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Sale::class)]
    private Collection $sales;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Type("string")]
    private ?string $discordId = null;

    #[ORM\Column]
    private ?bool $isTutorial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Classe $classe = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Server $server = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $youtubeUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $twitterUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ankamaUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $twitchUrl = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Price::class, orphanRemoval: true)]
    private Collection $prices;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable("now", new DateTimeZone('Europe/Paris'));
        $this->updatedAt = new \DateTimeImmutable("now", new DateTimeZone('Europe/Paris'));
        $this->records = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->isTutorial = false;

        $this->roles = ["ROLE_USER"];
        $this->prices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudonymeWebsite(): ?string
    {
        return $this->pseudonymeWebsite;
    }

    public function setPseudonymeWebsite(string $pseudonymeWebsite): self
    {
        $this->pseudonymeWebsite = $pseudonymeWebsite;

        return $this;
    }

    public function getPseudonymeDofus(): ?string
    {
        return $this->pseudonymeDofus;
    }

    public function setPseudonymeDofus(?string $pseudonymeDofus): self
    {
        $this->pseudonymeDofus = $pseudonymeDofus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Record>
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(Record $record): static
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
            $record->setUser($this);
        }

        return $this;
    }

    public function removeRecord(Record $record): static
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getUser() === $this) {
                $record->setUser(null);
            }
        }

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * @param File|null $image
     */
    public function setProfilePictureFile(?File $image = null): void
    {
        $this->profilePictureFile = $image;

        if (null !== $image) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }
    }

    public function getProfilePictureFile(): ?File
    {
        return $this->profilePictureFile;
    }

    public function getCoverPicture(): ?string
    {
        return $this->coverPicture;
    }

    public function setCoverPicture(?string $coverPicture): static
    {
        $this->coverPicture = $coverPicture;

        return $this;
    }

    /**
     * @param File|null $image
     */
    public function setCoverPictureFile(?File $image = null): void
    {
        $this->coverPictureFile = $image;

        if (null !== $image) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }
    }

    public function getCoverPictureFile(): ?File
    {
        return $this->coverPictureFile;
    }

    public function __serialize(): array
    {
        return [
            $this->id,
            $this->email,
            $this->password,
            $this->pseudonymeDofus,
            $this->pseudonymeWebsite,
            $this->roles,
            $this->contact,
            $this->description,
            $this->server,
            $this->classe
        ];
    }

    public function __unserialize(array $data): void
    {
        [
            $this->id,
            $this->email,
            $this->password,
            $this->pseudonymeDofus,
            $this->pseudonymeWebsite,
            $this->roles,
            $this->contact,
            $this->description,
            $this->server,
            $this->classe
        ] = $data;
    }

    /**
     * @return Collection<int, Sale>
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sale $sale): static
    {
        if (!$this->sales->contains($sale)) {
            $this->sales->add($sale);
            $sale->setUser($this);
        }

        return $this;
    }

    public function removeSale(Sale $sale): static
    {
        if ($this->sales->removeElement($sale)) {
            // set the owning side to null (unless already changed)
            if ($sale->getUser() === $this) {
                $sale->setUser(null);
            }
        }

        return $this;
    }

    public function getDiscordId(): ?string
    {
        return $this->discordId;
    }

    public function setDiscordId(?string $discordId): self
    {
        $this->discordId = $discordId;

        return $this;
    }

    public function __toString()
    {
        return $this->pseudonymeWebsite;
    }

    public function isIsTutorial(): ?bool
    {
        return $this->isTutorial;
    }

    public function setIsTutorial(bool $isTutorial): static
    {
        $this->isTutorial = $isTutorial;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

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

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): static
    {
        $this->server = $server;

        return $this;
    }

    public function getYoutubeUrl(): ?string
    {
        return $this->youtubeUrl;
    }

    public function setYoutubeUrl(?string $youtubeUrl): static
    {
        $this->youtubeUrl = $youtubeUrl;

        return $this;
    }

    public function getTwitterUrl(): ?string
    {
        return $this->twitterUrl;
    }

    public function setTwitterUrl(?string $twitterUrl): static
    {
        $this->twitterUrl = $twitterUrl;

        return $this;
    }

    public function getAnkamaUrl(): ?string
    {
        return $this->ankamaUrl;
    }

    public function setAnkamaUrl(?string $ankamaUrl): static
    {
        $this->ankamaUrl = $ankamaUrl;

        return $this;
    }

    public function getTwitchUrl(): ?string
    {
        return $this->twitchUrl;
    }

    public function setTwitchUrl(?string $twitchUrl): static
    {
        $this->twitchUrl = $twitchUrl;

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setUser($this);
        }

        return $this;
    }

    public function removePrice(Price $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getUser() === $this) {
                $price->setUser(null);
            }
        }

        return $this;
    }
}
