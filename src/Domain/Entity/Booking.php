<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ORM\Table(name: 'bookings')]
#[ORM\Index(name: 'booking__trip_id__idx', columns: ['trip_id'])]
#[ORM\Index(name: 'booking__passenger_id__idx', columns: ['passenger_id'])]
class Booking
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::BIGINT, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.id_generator_identity')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(name: 'trip_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Trip $trip;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(name: 'passenger_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $passenger;

    #[ORM\Column(name: 'seats_booked', type: 'integer')]
    private int $seatsBooked;

    #[ORM\Column(name: 'status', type: 'string', length: 50)]
    private string $status;

    #[ORM\Column(name : 'created_at', type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(name : 'updated_at', type: 'datetime')]
    private \DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function setTrip(Trip $trip): static
    {
        $this->trip = $trip;

        return $this;
    }

    public function getPassenger(): User
    {
        return $this->passenger;
    }

    public function setPassenger(User $passenger): static
    {
        $this->passenger = $passenger;

        return $this;
    }

    public function getSeatsBooked(): int
    {
        return $this->seatsBooked;
    }

    public function setSeatsBooked(int $seatsBooked): static
    {
        $this->seatsBooked = $seatsBooked;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
}
