<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $auteur = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(name: '`condition`', type: 'string', length: 255)]
    private ?string $condition = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column]
    private ?bool $forExchange = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;
#[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'books')]
#[ORM\JoinColumn(nullable: true)] // <-- IMPORTANT: 'true' permet de créer le livre sans utilisateur
private ?User $user = null;

/**
 * @var Collection<int, User>
 */
#[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoriteBooks')]
private Collection $users;

#[ORM\Column(length: 255, nullable: true)]
private ?string $description = null;

public function __construct()
{
    $this->users = new ArrayCollection();
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
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix !== null ? (float) $this->prix : null;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = (string) $prix;
        return $this;
    }

    public function isForExchange(): ?bool
    {
        return $this->forExchange;
    }

    public function setForExchange(bool $forExchange): self
    {
        $this->forExchange = $forExchange;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
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
            $user->addFavoriteBook($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavoriteBook($this);
        }

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
}