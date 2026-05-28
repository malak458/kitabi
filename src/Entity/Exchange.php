<?php

namespace App\Entity;

use App\Repository\ExchangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
class Exchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

     #[ORM\Column(type: Types::floatval, nullable: true)]
    private ?float $rate = 0.0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userRequesting = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userOffering = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $offeredBook = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $requestedBook = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getUserRequesting(): ?User
    {
        return $this->userRequesting;
    }

    public function setUserRequesting(?User $userRequesting): static
    {
        $this->userRequesting = $userRequesting;

        return $this;
    }

    public function getUserOffering(): ?User
    {
        return $this->userOffering;
    }

    public function setUserOffering(?User $userOffering): static
    {
        $this->userOffering = $userOffering;

        return $this;
    }

    public function getOfferedBook(): ?Book
    {
        return $this->offeredBook;
    }

    public function setOfferedBook(?Book $offeredBook): static
    {
        $this->offeredBook = $offeredBook;

        return $this;
    }

    public function getRequestedBook(): ?Book
    {
        return $this->requestedBook;
    }

    public function setRequestedBook(?Book $requestedBook): static
    {
        $this->requestedBook = $requestedBook;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}
