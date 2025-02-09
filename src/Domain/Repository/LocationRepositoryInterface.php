<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Location;

interface LocationRepositoryInterface
{
    public function add(Location $location): int;

    public function update(Location $location): int;

    public function remove(Location $location): void;

    public function findById(int $id): ?Location;

    public function findByName(string $name): ?Location;

    public function findByType(string $type): ?Location;

    public function findByLatitudeAndLongitude(string $latitude, string $longitude): ?Location;
}
