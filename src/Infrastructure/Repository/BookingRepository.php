<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Booking;
use App\Domain\Repository\BookingRepositoryInterface;

/**
 * @extends AbstractRepository<Booking>
 */
class BookingRepository extends AbstractRepository implements BookingRepositoryInterface
{
    public function add(Booking $booking): int
    {
        return $this->save($booking);
    }

    public function update(Booking $booking): int
    {
        return $this->save($booking);
    }

    public function remove(Booking $booking): void
    {
        $this->delete($booking);
    }

    public function findById(int $id): ?Booking
    {
        return $this->em->getRepository(Booking::class)->find($id);
    }

    // поиск бронирования по поездке
    public function findByTripId(int $tripId): array
    {
        return $this->em->getRepository(Booking::class)->findBy([
            'trip_id' => $tripId,
        ]);
    }

    // поиск бронирований по пассажиру
    public function findByUserId(int $userId): array
    {
        return $this->em->getRepository(Booking::class)->findBy([
            'passenger_id' => $userId,
        ]);
    }

    // поиск бронирований по статусу
    public function findByStatus(string $status): array
    {
        return $this->em->getRepository(Booking::class)->findBy([
            'status' => $status,
        ]);
    }

    // поиск бронирований по дате
    public function findByDate(\DateTime $date): array
    {
        return $this->em->getRepository(Booking::class)->findBy([
            'created_at' => $date,
        ]);
    }
}
