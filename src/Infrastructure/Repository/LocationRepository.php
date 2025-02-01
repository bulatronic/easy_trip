<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Location;

/**
 * @extends AbstractRepository<Location>
 */
class LocationRepository extends AbstractRepository
{
    // поиск точки по названию
    public function findByName(string $name): ?Location
    {
        return $this->entityManager->getRepository(Location::class)->findOneBy([
            'name' => $name,
        ]);
    }

    // поиск точки по типу
    public function findByType(string $type): ?Location
    {
        return $this->entityManager->getRepository(Location::class)->findOneBy([
            'type' => $type,
        ]);
    }

    // поиск точки по координатам
    public function findByLatitudeAndLongitude(string $latitude, string $longitude): ?Location
    {
        return $this->entityManager->getRepository(Location::class)->findOneBy([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
