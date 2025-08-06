<?php

namespace App\Tests\Domain\Service;

use App\Domain\Entity\Location;
use App\Domain\Entity\Trip;
use App\Domain\Entity\User;
use App\Domain\Model\Trip\CreateTripModel;
use App\Domain\Model\Trip\UpdateTripModel;
use App\Domain\Repository\TripRepositoryInterface;
use App\Domain\Service\LocationService;
use App\Domain\Service\TripService;
use App\Domain\Service\UserService;
use App\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TripServiceTest extends TestCase
{
    private TripService $tripService;
    private TripRepositoryInterface $tripRepository;
    private LocationService $locationService;
    private UserService $userService;

    protected function setUp(): void
    {
        $this->tripRepository = $this->createMock(TripRepositoryInterface::class);
        $this->locationService = $this->createMock(LocationService::class);
        $this->userService = $this->createMock(UserService::class);

        $this->tripService = new TripService(
            $this->tripRepository,
            $this->locationService,
            $this->userService
        );
    }

    public function testCreateTrip(): void
    {
        $driver = new User();
        $driver->setUsername('John Doe');
        $driver->setEmail('john@example.com');
        $driver->setPassword('password123');
        $driver->setRole(UserRole::ROLE_DRIVER->value);

        $startLocation = new Location();
        $startLocation->setName('Start Location');
        $startLocation->setType('city');
        $startLocation->setLatitude(10.0);
        $startLocation->setLongitude(20.0);

        $endLocation = new Location();
        $endLocation->setName('End Location');
        $endLocation->setType('city');
        $endLocation->setLatitude(30.0);
        $endLocation->setLongitude(40.0);

        $this->userService->expects($this->once())
            ->method('findUserById')
            ->with(1)
            ->willReturn($driver);

        $this->locationService->expects($this->exactly(2))
            ->method('findLocationById')
            ->willReturnCallback(function ($id) use ($startLocation, $endLocation) {
                return 1 === $id ? $startLocation : $endLocation;
            });

        $model = new CreateTripModel(
            driver_id: 1,
            start_location_id: 1,
            end_location_id: 2,
            departure_time: new \DateTime('+1 day'),
            available_seats: 4,
            price_per_seat: '100.00'
        );

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime($model->departure_time);
        $trip->setAvailableSeats($model->available_seats);
        $trip->setPricePerSeat($model->price_per_seat);
        $trip->setStatus('planned');

        $this->tripRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($trip) {
                return $trip instanceof Trip;
            }));

        $result = $this->tripService->create($model);

        $this->assertInstanceOf(Trip::class, $result);
        $this->assertEquals($driver, $result->getDriver());
        $this->assertEquals($startLocation, $result->getStartLocation());
        $this->assertEquals($endLocation, $result->getEndLocation());
        $this->assertEquals(4, $result->getAvailableSeats());
        $this->assertEquals('100.00', $result->getPricePerSeat());
        $this->assertEquals('planned', $result->getStatus());
    }

    public function testUpdateTrip(): void
    {
        $driver = new User();
        $driver->setUsername('John Doe');
        $driver->setEmail('john@example.com');
        $driver->setPassword('password123');
        $driver->setRole(UserRole::ROLE_DRIVER->value);

        $startLocation = new Location();
        $startLocation->setName('Start Location');
        $startLocation->setType('city');
        $startLocation->setLatitude(10.0);
        $startLocation->setLongitude(20.0);

        $endLocation = new Location();
        $endLocation->setName('End Location');
        $endLocation->setType('city');
        $endLocation->setLatitude(30.0);
        $endLocation->setLongitude(40.0);

        $existingTrip = new Trip();
        $existingTrip->setDriver($driver);
        $existingTrip->setStartLocation($startLocation);
        $existingTrip->setEndLocation($endLocation);
        $existingTrip->setDepartureTime(new \DateTime('+1 day'));
        $existingTrip->setAvailableSeats(4);
        $existingTrip->setPricePerSeat('100.00');
        $existingTrip->setStatus('planned');

        $this->tripRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingTrip);

        $this->userService->expects($this->once())
            ->method('findUserById')
            ->with(1)
            ->willReturn($driver);

        $this->locationService->expects($this->exactly(2))
            ->method('findLocationById')
            ->willReturnCallback(function ($id) use ($startLocation, $endLocation) {
                return 1 === $id ? $startLocation : $endLocation;
            });

        $model = new UpdateTripModel(
            driver_id: 1,
            start_location_id: 1,
            end_location_id: 2,
            departure_time: new \DateTime('+2 days'),
            available_seats: 3,
            price_per_seat: '150.00',
            status: 'planned',
            id: 1
        );

        $this->tripRepository->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($trip) {
                return $trip instanceof Trip;
            }));

        $result = $this->tripService->update(1, $model);

        $this->assertInstanceOf(Trip::class, $result);
        $this->assertEquals($driver, $result->getDriver());
        $this->assertEquals($startLocation, $result->getStartLocation());
        $this->assertEquals($endLocation, $result->getEndLocation());
        $this->assertEquals(3, $result->getAvailableSeats());
        $this->assertEquals('150.00', $result->getPricePerSeat());
        $this->assertEquals('planned', $result->getStatus());
    }

    public function testRemoveTrip(): void
    {
        $tripId = 1;
        $driver = new User();
        $startLocation = new Location();
        $endLocation = new Location();

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime(new \DateTime('+1 day'));
        $trip->setAvailableSeats(4);
        $trip->setPricePerSeat(100.0);
        $trip->setStatus('active');

        $this->tripRepository->expects($this->once())
            ->method('findById')
            ->with($tripId)
            ->willReturn($trip);

        $this->tripRepository->expects($this->once())
            ->method('remove')
            ->with($trip);

        $this->tripService->remove($tripId);
    }

    public function testFindTripById(): void
    {
        $tripId = 1;
        $driver = new User();
        $startLocation = new Location();
        $endLocation = new Location();

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime(new \DateTime('+1 day'));
        $trip->setAvailableSeats(4);
        $trip->setPricePerSeat(100.0);
        $trip->setStatus('active');

        $this->tripRepository->expects($this->once())
            ->method('findById')
            ->with($tripId)
            ->willReturn($trip);

        $result = $this->tripService->findTripById($tripId);

        $this->assertInstanceOf(Trip::class, $result);
        $this->assertEquals($driver, $result->getDriver());
        $this->assertEquals($startLocation, $result->getStartLocation());
        $this->assertEquals($endLocation, $result->getEndLocation());
        $this->assertEquals(4, $result->getAvailableSeats());
        $this->assertEquals(100.0, $result->getPricePerSeat());
        $this->assertEquals('active', $result->getStatus());
    }

    public function testFindTripByIdNotFound(): void
    {
        $tripId = 1;

        $this->tripRepository->expects($this->once())
            ->method('findById')
            ->with($tripId)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('Поездка с id %d не найдена.', $tripId));

        $this->tripService->findTripById($tripId);
    }

    public function testCompleteTrip(): void
    {
        $tripId = 1;
        $driver = new User();
        $startLocation = new Location();
        $endLocation = new Location();

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime(new \DateTime('+1 day'));
        $trip->setAvailableSeats(4);
        $trip->setPricePerSeat(100.0);
        $trip->setStatus('active');

        $this->tripRepository->expects($this->once())
            ->method('findById')
            ->with($tripId)
            ->willReturn($trip);

        $this->tripRepository->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($trip) {
                return 'completed' === $trip->getStatus();
            }));

        $this->tripService->completeTrip($tripId);
    }

    public function testCancelTrip(): void
    {
        $driver = new User();
        $startLocation = new Location();
        $endLocation = new Location();

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime(new \DateTime('+1 day'));
        $trip->setAvailableSeats(4);
        $trip->setPricePerSeat(100.0);
        $trip->setStatus('active');

        $this->tripRepository->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($trip) {
                return 'cancelled' === $trip->getStatus();
            }));

        $this->tripService->cancelTrip($trip);
    }

    public function testUpdateAvailableSeats(): void
    {
        $tripId = 1;
        $newSeats = 2;
        $driver = new User();
        $startLocation = new Location();
        $endLocation = new Location();

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime(new \DateTime('+1 day'));
        $trip->setAvailableSeats(4);
        $trip->setPricePerSeat(100.0);
        $trip->setStatus('active');

        $this->tripRepository->expects($this->once())
            ->method('findById')
            ->with($tripId)
            ->willReturn($trip);

        $this->tripRepository->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($trip) use ($newSeats) {
                return $trip->getAvailableSeats() === $newSeats;
            }));

        $this->tripService->updateAvailableSeats($tripId, $newSeats);
    }
}
