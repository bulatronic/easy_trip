<?php

namespace App\Controller\API\Review\Update;

use App\Domain\Model\Review\UpdateReviewModel;
use App\Domain\Service\ModelFactory;
use App\Domain\Service\ReviewService;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<UpdateReviewModel> */
        private ModelFactory $modelFactory,
        private ReviewService $service,
    ) {
    }

    public function update(int $id, InputReviewDTO $dto): OutputReviewDTO
    {
        $updateReviewModel = $this->modelFactory->makeModel(
            UpdateReviewModel::class,
            $id,
            $dto->user,
            $dto->trip,
            $dto->rating,
            $dto->comment,
        );

        $review = $this->service->update($id, $updateReviewModel);

        return new OutputReviewDTO(
            id: $review->getId(),
            user: $review->getUser()->getId(),
            trip: $review->getTrip()->getId(),
            rating: $review->getRating(),
            comment: $review->getComment(),
        );
    }
}
