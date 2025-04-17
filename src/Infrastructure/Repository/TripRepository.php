<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Trip;
use App\Domain\Entity\User;
use App\Domain\Repository\TripRepositoryInterface;

/**
 * @extends AbstractRepository<Trip>
 */
class TripRepository extends AbstractRepository implements TripRepositoryInterface
{
    public function add(Trip $trip): int
    {
        return $this->save($trip);
    }

    public function update(Trip $trip): int
    {
        return $this->save($trip);
    }

    public function remove(Trip $trip): void
    {
        $this->delete($trip);
    }

    // поиск поездок по водителю
    public function findByDriver(int $driverId): array
    {
        return $this->em->getRepository(Trip::class)->findBy([
            'driver_id' => $driverId,
        ]);
    }

    // поиск поездок по начальной точке
    public function findByStartLocation(int $startLocationId): array
    {
        return $this->em->getRepository(Trip::class)->findBy([
            'start_location_id' => $startLocationId,
        ]);
    }

    // поиск поездок по конечной точке
    public function findByEndLocation(int $endLocationId): array
    {
        return $this->em->getRepository(Trip::class)->findBy([
            'end_location_id' => $endLocationId,
        ]);
    }

    // поиск поездок по начальной и конечной точке
    public function findByStartAndEndLocation(int $startLocationId, int $endLocationId): array
    {
        return $this->em->getRepository(Trip::class)->findBy([
            'start_location_id' => $startLocationId,
            'end_location_id' => $endLocationId,
        ]);
    }

    // поиск доступных поездок по начальной и конечной точке, дате и времени
    public function findAvailableTrips(int $startLocationId, int $endLocationId, \DateTime $departureTime): array
    {
        return $this->em->getRepository(Trip::class)->findBy([
            'start_location_id' => $startLocationId,
            'end_location_id' => $endLocationId,
            'departure_time' => $departureTime,
        ]);
    }

    public function findById(int $id): ?Trip
    {
        return $this->em->getRepository(Trip::class)->find($id);
    }

    public function findByDriverAndDateRange(User $driver, \DateTime $startDate, \DateTime $endDate): array
    {
        return $this->em->getRepository(Trip::class)->createQueryBuilder('t')
            ->where('t.driver = :driver')
            ->andWhere('t.departureTime BETWEEN :startDate AND :endDate')
            ->setParameter('driver', $driver)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->em->getRepository(Trip::class)->createQueryBuilder('t')
            ->where('t.departureTime BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }
}
