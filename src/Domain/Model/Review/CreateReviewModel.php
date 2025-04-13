<?php

namespace App\Domain\Model\Review;

use App\Domain\Entity\Trip;
use App\Domain\Entity\User;
use App\Domain\Service\TripService;
use App\Domain\Service\UserService;

class CreateReviewModel
{
    private UserService $userService;
    private TripService $tripService;

    public function __construct(
        public int $user,
        public int $trip,
        public int $rating,
        public ?string $comment,
    ) {
    }

    public function setServices(UserService $userService, TripService $tripService): void
    {
        $this->userService = $userService;
        $this->tripService = $tripService;
    }

    public function getUser(): User
    {
        return $this->userService->findUserById($this->user);
    }

    public function getTrip(): Trip
    {
        return $this->tripService->findTripById($this->trip);
    }
}
