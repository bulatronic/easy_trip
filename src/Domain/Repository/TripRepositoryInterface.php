<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Trip;

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
}
