<?php

namespace App\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'statistics')]
#[ORM\Index(name: 'statistics__period_type__idx', columns: ['period_type'])]
#[ORM\Index(name: 'statistics__created_at__idx', columns: ['created_at'])]
#[ORM\Index(name: 'statistics__driver_id__idx', columns: ['driver_id'])]
#[ORM\Index(
    name: 'statistics__period_type_start_date_end_date__idx',
    columns: ['period_type', 'start_date', 'end_date']
)]
#[ORM\HasLifecycleCallbacks]
class Statistics implements EntityInterface
{
    public const TYPE_PERSONAL = 'personal';
    public const TYPE_GLOBAL = 'global';

    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::BIGINT, unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'driver_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $driver = null;

    #[ORM\Column(name: 'statistics_type', type: Types::STRING, length: 20)]
    private string $statisticsType;

    #[ORM\Column(name: 'period_type', type: Types::STRING, length: 20)]
    private string $periodType;

    #[ORM\Column(name: 'start_date', type: Types::DATETIME_MUTABLE)]
    private \DateTime $startDate;

    #[ORM\Column(name: 'end_date', type: Types::DATETIME_MUTABLE)]
    private \DateTime $endDate;

    #[ORM\Column(name: 'total_trips', type: Types::INTEGER)]
    private int $totalTrips = 0;

    #[ORM\Column(name: 'total_revenue', type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $totalRevenue = '0.00';

    #[ORM\Column(name: 'total_passengers', type: Types::INTEGER)]
    private int $totalPassengers = 0;

    #[ORM\Column(name: 'average_price', type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $averagePrice = '0.00';

    #[ORM\Column(name: 'average_passengers', type: Types::DECIMAL, precision: 5, scale: 2)]
    private string $averagePassengers = '0.00';

    #[ORM\Column(name: 'average_rating', type: Types::DECIMAL, precision: 3, scale: 2)]
    private string $averageRating = '0.00';

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): void
    {
        $this->driver = $driver;
    }

    public function getStatisticsType(): string
    {
        return $this->statisticsType;
    }

    public function setStatisticsType(string $statisticsType): void
    {
        $this->statisticsType = $statisticsType;
    }

    public function getPeriodType(): string
    {
        return $this->periodType;
    }

    public function setPeriodType(string $periodType): void
    {
        $this->periodType = $periodType;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getTotalTrips(): int
    {
        return $this->totalTrips;
    }

    public function setTotalTrips(int $totalTrips): void
    {
        $this->totalTrips = $totalTrips;
    }

    public function getTotalRevenue(): string
    {
        return $this->totalRevenue;
    }

    public function setTotalRevenue(string $totalRevenue): void
    {
        $this->totalRevenue = $totalRevenue;
    }

    public function getTotalPassengers(): int
    {
        return $this->totalPassengers;
    }

    public function setTotalPassengers(int $totalPassengers): void
    {
        $this->totalPassengers = $totalPassengers;
    }

    public function getAveragePrice(): string
    {
        return $this->averagePrice;
    }

    public function setAveragePrice(string $averagePrice): void
    {
        $this->averagePrice = $averagePrice;
    }

    public function getAveragePassengers(): string
    {
        return $this->averagePassengers;
    }

    public function setAveragePassengers(string $averagePassengers): void
    {
        $this->averagePassengers = $averagePassengers;
    }

    public function getAverageRating(): string
    {
        return $this->averageRating;
    }

    public function setAverageRating(string $averageRating): void
    {
        $this->averageRating = $averageRating;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
