<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;  // ← AJOUTER CET IMPORT
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;  // ← AJOUTER CET IMPORT

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface  // ← AJOUTER LES INTERFACES
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]  // ← AJOUTER unique: true
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: 'json')]  // ← AJOUTER LES ROLES
    private array $roles = [];

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'user')]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->roles = ['ROLE_USER'];  // Rôle par défaut
    }

    // ===== MÉTHODES REQUISES PAR UserInterface =====
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    // Identifiant unique pour l'authentification (utilisé par Symfony)
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // Alias de getUserIdentifier (déprécié mais parfois requis)
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    // Récupérer les rôles de l'utilisateur
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantir que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    // Définir les rôles
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    // Récupérer le mot de passe
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Définir le mot de passe
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    // Effacer les informations sensibles (nécessaire pour l'interface)
    public function eraseCredentials(): void
    {
        // Ne rien faire ici
    }

    // ===== VOS AUTRES GETTERS/SETTERS =====

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;
        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setUser($this);
        }
        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            if ($book->getUser() === $this) {
                $book->setUser(null);
            }
        }
        return $this;
    }
}