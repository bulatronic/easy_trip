<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Trip;

/**
 * @extends AbstractRepository<Trip>
 */
class TripRepository extends AbstractRepository
{
    // поиск поездок по водителю
    public function findByDriver(int $driverId): array
    {
        return $this->entityManager->getRepository(Trip::class)->findBy([
            'driver_id' => $driverId,
        ]);
    }

    // поиск поездок по начальной точке
    public function findByStartLocation(int $startLocationId): array
    {
        return $this->entityManager->getRepository(Trip::class)->findBy([
            'start_location_id' => $startLocationId,
        ]);
    }

    // поиск поездок по конечной точке
    public function findByEndLocation(int $endLocationId): array
    {
        return $this->entityManager->getRepository(Trip::class)->findBy([
            'end_location_id' => $endLocationId,
        ]);
    }

    // поиск поездок по начальной и конечной точке
    public function findByStartAndEndLocation(int $startLocationId, int $endLocationId): array
    {
        return $this->entityManager->getRepository(Trip::class)->findBy([
            'start_location_id' => $startLocationId,
            'end_location_id' => $endLocationId,
        ]);
    }

    // поиск поездок по дате
    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->entityManager->getRepository(Trip::class)->findBy([
            'departure_time' => $startDate,
            'departure_time' => $endDate,
        ]);
    }

    // поиск доступных поездок по начальной и конечной точке, дате и времени
    public function findAvailableTrips(int $startLocationId, int $endLocationId, \DateTime $departureTime): array
    {
        return $this->entityManager->getRepository(Trip::class)->findBy([
            'start_location_id' => $startLocationId,
            'end_location_id' => $endLocationId,
            'departure_time' => $departureTime,
        ]);
    }
}
