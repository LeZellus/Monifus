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

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudonymeWebsite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudonymeDofus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Monitor::class, orphanRemoval: true)]
    private Collection $monitors;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Record::class, orphanRemoval: true)]
    private Collection $records;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[Vich\UploadableField(mapping: 'user_profile', fileNameProperty: 'profilePicture')]
    private ?File $profilePictureFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverPicture = null;

    #[Vich\UploadableField(mapping: 'user_cover', fileNameProperty: 'coverPicture')]
    private ?File $coverPictureFile = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Sale::class)]
    private Collection $sales;

    #[ORM\Column(nullable: true)]
    private ?int $discordId = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable("now", new DateTimeZone('Europe/Paris'));
        $this->updatedAt = new \DateTimeImmutable("now", new DateTimeZone('Europe/Paris'));
        $this->monitors = new ArrayCollection();
        $this->records = new ArrayCollection();
        $this->sales = new ArrayCollection();

        // Valeur par défaut pour pseudonymeWebsite et pseudonymeDofus
        $this->pseudonymeWebsite = 'Anonyme' . uniqid();
        $this->pseudonymeDofus = 'Anonyme' . uniqid();
        $this->roles = ["ROLE_USER"];
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
     * @return Collection<int, Monitor>
     */
    public function getMonitors(): Collection
    {
        return $this->monitors;
    }

    public function addMonitor(Monitor $monitor): self
    {
        if (!$this->monitors->contains($monitor)) {
            $this->monitors->add($monitor);
            $monitor->setUser($this);
        }

        return $this;
    }

    public function removeMonitor(Monitor $monitor): self
    {
        if ($this->monitors->removeElement($monitor)) {
            // set the owning side to null (unless already changed)
            if ($monitor->getUser() === $this) {
                $monitor->setUser(null);
            }
        }

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
            $this->roles
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
            $this->roles
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

    public function getDiscordId(): ?int
    {
        return $this->discordId;
    }

    public function setDiscordId(int $discordId): static
    {
        $this->discordId = $discordId;

        return $this;
    }
}
