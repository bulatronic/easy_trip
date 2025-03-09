<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Booking;

interface BookingRepositoryInterface
{
    public function add(Booking $booking): int;

    public function update(Booking $booking): int;

    public function remove(Booking $booking): void;

    public function findByTripId(int $tripId): array;

    public function findByUserId(int $userId): array;

    public function findByStatus(string $status): array;

    public function findByDate(\DateTime $date): array;
}
