<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Booking;

/**
 * @extends AbstractRepository<Booking>
 */
class BookingRepository extends AbstractRepository
{
    // поиск бронирования по поездке
    public function findByTripId(int $tripId): array
    {
        return $this->entityManager->getRepository(Booking::class)->findBy([
            'trip_id' => $tripId,
        ]);
    }

    // поиск бронирований по пассажиру
    public function findByUserId(int $userId): array
    {
        return $this->entityManager->getRepository(Booking::class)->findBy([
            'passenger_id' => $userId,
        ]);
    }

    // поиск бронирований по статусу
    public function findByStatus(string $status): array
    {
        return $this->entityManager->getRepository(Booking::class)->findBy([
            'status' => $status,
        ]);
    }

    // поиск бронирований по дате
    public function findByDate(\DateTime $date): array
    {
        return $this->entityManager->getRepository(Booking::class)->findBy([
            'created_at' => $date,
        ]);
    }
}
