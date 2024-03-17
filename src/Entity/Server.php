<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $community = null;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: Guild::class)]
    private Collection $guilds;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->guilds = new ArrayCollection();
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

    public function getCommunity(): ?string
    {
        return $this->community;
    }

    public function setCommunity(string $community): static
    {
        $this->community = $community;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setServer($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getServer() === $this) {
                $user->setServer(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Guild>
     */
    public function getGuilds(): Collection
    {
        return $this->guilds;
    }

    public function addGuild(Guild $guild): static
    {
        if (!$this->guilds->contains($guild)) {
            $this->guilds->add($guild);
            $guild->setServer($this);
        }

        return $this;
    }

    public function removeGuild(Guild $guild): static
    {
        if ($this->guilds->removeElement($guild)) {
            // set the owning side to null (unless already changed)
            if ($guild->getServer() === $this) {
                $guild->setServer(null);
            }
        }

        return $this;
    }
}
