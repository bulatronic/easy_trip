<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'user__email__uniq', columns: ['email'])]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::BIGINT, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.id_generator_identity')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 50)]
    private string $role;

    #[ORM\Column(name : 'created_at', type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime')]
    private \DateTime $updatedAt;

    // Связи
    #[ORM\OneToMany(targetEntity: Trip::class, mappedBy: 'driver')]
    private Collection $trips;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'passenger')]
    private Collection $bookings;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function setTrips(Collection $trips): self
    {
        $this->trips = $trips;

        return $this;
    }

    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function setBookings(Collection $bookings): self
    {
        $this->bookings = $bookings;

        return $this;
    }
}
