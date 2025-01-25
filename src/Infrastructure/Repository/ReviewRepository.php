<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Review;

/**
 * @extends AbstractRepository<Review>
 */
class ReviewRepository extends AbstractRepository
{
    // поиск отзывов по поездке
    public function findByTripId(int $tripId): array
    {
        return $this->entityManager->getRepository(Review::class)->findBy([
            'trip_id' => $tripId,
        ]);
    }

    // поиск отзывов по пользователю
    public function findByUserId(int $userId): array
    {
        return $this->entityManager->getRepository(Review::class)->findBy([
            'user_id' => $userId,
        ]);
    }

    // поиск отзывов по диапазону рейтинга
    public function findByRatingRange(int $min, int $max): array
    {
        return $this->entityManager->getRepository(Review::class)->findBy([
            'rating' => $min,
            'rating' => $max,
        ]);
    }
}
