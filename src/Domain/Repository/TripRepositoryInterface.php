<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Trip;
use App\Domain\Entity\User;

interface TripRepositoryInterface
{
    public function add(Trip $trip): int;

    public function update(Trip $trip): int;

    public function remove(Trip $trip): void;

    public function findByDriver(int $driverId): array;

    public function findByStartLocation(int $startLocationId): array;

    public function findByEndLocation(int $endLocationId): array;

    public function findByStartAndEndLocation(int $startLocationId, int $endLocationId): array;

    public function findAvailableTrips(int $startLocationId, int $endLocationId, \DateTime $departureTime): array;

    public function findById(int $id): ?Trip;

    public function findByDriverAndDateRange(User $driver, \DateTime $startDate, \DateTime $endDate): array;

    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array;
}
