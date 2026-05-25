<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    public function getId(): ?int { return $this->id; }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function getBook(): ?Book { return $this->book; }
    public function setBook(?Book $book): static
    {
        $this->book = $book;
        return $this;
    }
}