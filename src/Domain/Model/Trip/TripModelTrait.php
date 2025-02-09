<?php

namespace App\Domain\Model\Trip;

use App\Domain\Entity\Location;
use App\Domain\Entity\User;
use App\Domain\Service\LocationService;
use App\Domain\Service\UserService;

trait TripModelTrait
{
    private UserService $userService;
    private LocationService $locationService;

    public function setServices(UserService $userService, LocationService $locationService): void
    {
        $this->userService = $userService;
        $this->locationService = $locationService;
    }

    public function getDriver(): User
    {
        return $this->userService->findUserById($this->driver_id);
    }

    public function getStartLocation(): Location
    {
        return $this->locationService->findLocationById($this->start_location_id);
    }

    public function getEndLocation(): Location
    {
        return $this->locationService->findLocationById($this->end_location_id);
    }
}
