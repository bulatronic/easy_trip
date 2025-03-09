<?php

namespace App\Domain\Model\Booking;

use App\Domain\Entity\Trip;
use App\Domain\Entity\User;
use App\Domain\Service\TripService;
use App\Domain\Service\UserService;

trait BookingModelTrait
{
    private UserService $userService;
    private TripService $tripService;

    public function setServices(UserService $userService, TripService $tripService): void
    {
        $this->userService = $userService;
        $this->tripService = $tripService;
    }

    public function getPassenger(): User
    {
        return $this->userService->findUserById($this->passenger);
    }

    public function getTrip(): Trip
    {
        return $this->tripService->findTripById($this->trip);
    }
}
