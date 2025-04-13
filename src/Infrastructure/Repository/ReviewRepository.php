<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Review;
use App\Domain\Repository\ReviewRepositoryInterface;

/**
 * @extends AbstractRepository<Review>
 */
class ReviewRepository extends AbstractRepository implements ReviewRepositoryInterface
{
    public function add(Review $review): int
    {
        return $this->save($review);
    }

    public function update(Review $review): int
    {
        return $this->save($review);
    }

    public function remove(Review $review): void
    {
        $this->delete($review);
    }

    public function findById(int $id): ?Review
    {
        return $this->em->getRepository(Review::class)->find($id);
    }

    // поиск отзывов по поездке
    public function findByTripId(int $tripId): array
    {
        return $this->em->getRepository(Review::class)->findBy([
            'trip_id' => $tripId,
        ]);
    }

    // поиск отзывов по пользователю
    public function findByUserId(int $userId): array
    {
        return $this->em->getRepository(Review::class)->findBy([
            'user_id' => $userId,
        ]);
    }

    // поиск отзыва по поездке и пользователю
    public function findByTripIdAndUserId(int $tripId, int $userId): ?Review
    {
        return $this->em->getRepository(Review::class)->findOneBy([
            'trip' => $tripId,
            'user' => $userId,
        ]);
    }
}
