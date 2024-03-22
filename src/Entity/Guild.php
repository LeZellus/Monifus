<?php

namespace App\Entity;

use App\Repository\GuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: GuildRepository::class)]
class Guild
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s-]*$/',
        message: 'Lettres et espaces uniquement'
    )]
    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $discordUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $websiteUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $blasonPicture = null;

    #[Vich\UploadableField(mapping: 'guild_blason', fileNameProperty: 'blasonPicture')]
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
    private ?File $blasonPictureFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverPicture = null;

    #[Vich\UploadableField(mapping: 'guild_cover', fileNameProperty: 'coverPicture')]
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

    #[ORM\ManyToOne(inversedBy: 'guilds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Server $server = null;

    #[ORM\OneToMany(mappedBy: 'guild', targetEntity: User::class)]
    private Collection $members;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $leader = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: 'Le niveau doit être entre 1 et 200',
        min: 1,
        max: 200
    )]
    private ?int $minimumLevel = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: 'Le niveau doit être entre 1 et 200',
        min: 1,
        max: 200
    )]
    private ?int $level = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: 'Le niveau doit être entre 1 et 22000',
        min: 1,
        max: 22000
    )]
    private ?int $minimumSuccess = null;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDiscordUrl(): ?string
    {
        return $this->discordUrl;
    }

    public function setDiscordUrl(string $discordUrl): static
    {
        $this->discordUrl = $discordUrl;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(string $websiteUrl): static
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getBlasonPicture(): ?string
    {
        return $this->blasonPicture;
    }

    public function setBlasonPicture(?string $blasonPicture): static
    {
        $this->blasonPicture = $blasonPicture;

        return $this;
    }

    /**
     * @param File|null $image
     */
    public function setBlasonPictureFile(?File $image = null): void
    {
        $this->blasonPictureFile = $image;

        if (null !== $image) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }
    }

    public function getBlasonPictureFile(): ?File
    {
        return $this->blasonPictureFile;
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

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): static
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setGuild($this);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getGuild() === $this) {
                $member->setGuild(null);
            }
        }

        return $this;
    }

    public function getLeader(): ?User
    {
        return $this->leader;
    }

    public function setLeader(User $leader): static
    {
        $this->leader = $leader;

        return $this;
    }

    public function getMinimumLevel(): ?int
    {
        return $this->minimumLevel;
    }

    public function setMinimumLevel(int $minimumLevel): static
    {
        $this->minimumLevel = $minimumLevel;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getMinimumSuccess(): ?int
    {
        return $this->minimumSuccess;
    }

    public function setMinimumSuccess(int $minimumSuccess): static
    {
        $this->minimumSuccess = $minimumSuccess;

        return $this;
    }
}
