<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre est requis.")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'auteur est requis.")]
    private ?string $auteur = null;

    #[ORM\Column(type: 'integer')]  // ← Changé de string à integer
    #[Assert\NotBlank(message: "Le nombre de pages est requis.")]
    #[Assert\Positive(message: "Le nombre de pages doit être positif.")]
    private ?int $totalPages = null;

    #[ORM\Column(length: 50)]
    private ?string $type = 'live';

    #[ORM\Column(type: 'integer')]  // ← Changé de string à integer
    #[Assert\NotBlank(message: "Le nombre de participants est requis.")]
    #[Assert\Range(min: 2, max: 50)]
    private ?int $maxParticipants = 15;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le genre est requis.")]
    private ?string $genre = null;

    #[ORM\Column(type: 'text', nullable: true)]  // ← Changé de string à text
    private ?string $tags = null;

    #[ORM\Column(type: 'text', nullable: true)]  // ← Changé de string à text
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = 'default-book.jpg';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $host = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $scheduledAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // ========== GETTERS & SETTERS ==========

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): static
    {
        $this->totalPages = $totalPages;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): static
    {
        $this->maxParticipants = $maxParticipants;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;
        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): static
    {
        $this->tags = $tags;
        return $this;
    }

    public function getTagsArray(): array
    {
        if (!$this->tags) {
            return [];
        }
        return explode(',', $this->tags);
    }

    public function setTagsFromArray(array $tags): static
    {
        $this->tags = implode(',', array_filter($tags));
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(?User $host): static
    {
        $this->host = $host;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getScheduledAt(): ?\DateTime
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?\DateTime $scheduledAt): static
    {
        $this->scheduledAt = $scheduledAt;
        return $this;
    }
}