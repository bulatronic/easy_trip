<?php

namespace App\Domain\Service;

use App\Domain\Entity\Review;
use App\Domain\Entity\Trip;
use App\Domain\Entity\User;
use App\Domain\Exception\ValidationException;
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
        $this->validateReviewCreation($model->getTrip(), $model->getUser());

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
            throw new NotFoundHttpException(sprintf('Отзыв с id %d не найден.', $id));
        }

        return $review;
    }

    public function validateReviewCreation(Trip $trip, User $user): void
    {
        // Проверка, что пользователь участвовал в поездке
        $isParticipant = false;
        foreach ($trip->getBookings() as $booking) {
            if ($booking->getPassenger()->getId() === $user->getId()) {
                $isParticipant = true;
                break;
            }
        }

        if ($isParticipant) {
            throw new ValidationException('Только участники поездки могут оставлять отзывы');
        }

        // Проверка, что поездка завершена (проверяем по времени отправления)
        if ($trip->getDepartureTime() > new \DateTime()) {
            throw new ValidationException('Отзыв можно оставить только после завершения поездки');
        }

        // Проверка, что пользователь еще не оставлял отзыв
        $existingReview = $this->reviewRepository->findByTripIdAndUserId($trip->getId(), $user->getId());
        if ($existingReview) {
            throw new ValidationException('Вы уже оставляли отзыв на эту поездку');
        }
    }
}
