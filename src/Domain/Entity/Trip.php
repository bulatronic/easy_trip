<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'trips')]
#[ORM\Index(name: 'trip__driver_id__idx', columns: ['driver_id'])]
#[ORM\Index(name: 'trip__start_location_id__idx', columns: ['start_location_id'])]
#[ORM\Index(name: 'trip__end_location_id__idx', columns: ['end_location_id'])]
class Trip implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::BIGINT, unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'trips')]
    #[ORM\JoinColumn(name: 'driver_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $driver;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: 'start_location_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Location $startLocation;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: 'end_location_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Location $endLocation;

    #[ORM\Column(name: 'departure_time', type: 'datetime')]
    private \DateTime $departureTime;

    #[ORM\Column(name: 'available_seats', type: 'integer')]
    private int $availableSeats;

    #[ORM\Column(name: 'price_per_seat', type: 'decimal', precision: 10, scale: 2)]
    private string $pricePerSeat;

    #[ORM\Column(name: 'status', type: 'string', length: 50)]
    private string $status;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime')]
    private \DateTime $updatedAt;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'trip')]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDriver(): User
    {
        return $this->driver;
    }

    public function setDriver(User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getStartLocation(): Location
    {
        return $this->startLocation;
    }

    public function setStartLocation(Location $startLocation): static
    {
        $this->startLocation = $startLocation;

        return $this;
    }

    public function getEndLocation(): Location
    {
        return $this->endLocation;
    }

    public function setEndLocation(Location $endLocation): static
    {
        $this->endLocation = $endLocation;

        return $this;
    }

    public function getDepartureTime(): \DateTime
    {
        return $this->departureTime;
    }

    public function setDepartureTime(\DateTime $departureTime): static
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getAvailableSeats(): int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): static
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }

    public function getPricePerSeat(): float
    {
        return $this->pricePerSeat;
    }

    public function setPricePerSeat(float $pricePerSeat): static
    {
        $this->pricePerSeat = $pricePerSeat;

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

    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function setBookings(Collection $bookings): static
    {
        $this->bookings = $bookings;

        return $this;
    }
}
