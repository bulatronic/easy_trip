<?php

namespace App\Controller\API\Review\Delete;

use App\Domain\Service\ReviewService;

readonly class Manager
{
    public function __construct(
        private ReviewService $service,
    ) {
    }

    public function deleteReview(int $id): void
    {
        $this->service->remove($id);
    }
}
