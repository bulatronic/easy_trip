<?php

namespace App\Domain\Service;

use App\Domain\Entity\Review;
use App\Domain\Model\Review\CreateReviewModel;
use App\Domain\Model\Review\UpdateReviewModel;
use App\Domain\Repository\ReviewRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class ReviewService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
        private UserService $userService,
        private TripService $tripService,
    ) {
    }

    public function create(CreateReviewModel $model): Review
    {
        $model->setServices($this->userService, $this->tripService);

        $review = new Review();
        $review->setUser($model->getUser());
        $review->setTrip($model->getTrip());
        $review->setRating($model->rating);
        $review->setComment($model->comment);
        $review->setCreatedAt();
        $this->reviewRepository->add($review);

        return $review;
    }

    public function update(int $id, UpdateReviewModel $model): Review
    {
        $review = $this->getReviewOrFail($id);
        $model->updateReview($review);

        $this->reviewRepository->update($review);

        return $review;
    }

    public function remove(int $id): void
    {
        $review = $this->getReviewOrFail($id);
        $this->reviewRepository->remove($review);
    }

    public function findReviewById(int $id): ?Review
    {
        return $this->getReviewOrFail($id);
    }

    private function getReviewOrFail(int $id): Review
    {
        $review = $this->reviewRepository->findById($id);

        if (null === $review) {
            throw new NotFoundHttpException(sprintf('Review with id %d not found.', $id));
        }

        return $review;
    }
}
