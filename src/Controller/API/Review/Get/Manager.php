<?php

namespace App\Controller\API\Review\Get;

use App\Domain\Service\ReviewService;

readonly class Manager
{
    public function __construct(
        private ReviewService $service,
    ) {
    }

    public function get(int $id): OutputReviewDTO
    {
        $review = $this->service->findReviewById($id);

        return new OutputReviewDTO(
            id: $review->getId(),
            user: $review->getUser()->getId(),
            trip: $review->getTrip()->getId(),
            rating: $review->getRating(),
            comment: $review->getComment(),
        );
    }
}
