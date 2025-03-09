<?php

namespace App\Controller\API\Review\Create;

use App\Domain\Model\Review\CreateReviewModel;
use App\Domain\Service\ModelFactory;
use App\Domain\Service\ReviewService;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<CreateReviewModel> */
        private ModelFactory $modelFactory,
        private ReviewService $service,
    ) {
    }

    public function create(InputReviewDTO $dto): OutputReviewDTO
    {
        $createReviewModel = $this->modelFactory->makeModel(
            CreateReviewModel::class,
            $dto->user,
            $dto->trip,
            $dto->rating,
            $dto->comment,
        );

        $review = $this->service->create($createReviewModel);

        return new OutputReviewDTO(
            id: $review->getId(),
            user: $review->getUser()->getId(),
            trip: $review->getTrip()->getId(),
            rating: $review->getRating(),
            comment: $review->getComment(),
        );
    }
}
