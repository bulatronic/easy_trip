<?php

namespace App\Domain\Model\Review;

use App\Domain\Entity\Review;

class UpdateReviewModel
{
    public function __construct(
        public int $id,
        public int $user,
        public int $trip,
        public ?int $rating,
        public ?string $comment,
    ) {
    }

    public function updateReview(Review $review): void
    {
        if (null !== $this->rating) {
            $review->setRating($this->rating);
        }
        if (null !== $this->comment) {
            $review->setComment($this->comment);
        }
        $review->setUpdatedAt();
    }
}
