<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Location;
use App\Domain\Repository\LocationRepositoryInterface;

/**
 * @extends AbstractRepository<Location>
 */
class LocationRepository extends AbstractRepository implements LocationRepositoryInterface
{
    public function add(Location $location): int
    {
        return $this->save($location);
    }

    public function update(Location $location): int
    {
        return $this->save($location);
    }

    public function remove(Location $location): void
    {
        $this->delete($location);
    }

    public function findById(int $id): ?Location
    {
        return $this->em->getRepository(Location::class)->find($id);
    }

    // поиск точки по названию
    public function findByName(string $name): ?Location
    {
        return $this->em->getRepository(Location::class)->findOneBy([
            'name' => $name,
        ]);
    }

    // поиск точки по типу
    public function findByType(string $type): ?Location
    {
        return $this->em->getRepository(Location::class)->findOneBy([
            'type' => $type,
        ]);
    }

    // поиск точки по координатам
    public function findByLatitudeAndLongitude(string $latitude, string $longitude): ?Location
    {
        return $this->em->getRepository(Location::class)->findOneBy([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
