<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Review;

interface ReviewRepositoryInterface
{
    public function add(Review $review): int;

    public function update(Review $review): int;

    public function remove(Review $review): void;

    public function findById(int $id): ?Review;

    public function findByTripId(int $tripId): array;

    public function findByUserId(int $userId): array;
}
