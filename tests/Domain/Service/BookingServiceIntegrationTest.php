<?php

namespace App\Tests\Domain\Service;

use App\Domain\Entity\Booking;
use App\Domain\Entity\Location;
use App\Domain\Entity\Trip;
use App\Domain\Entity\User;
use App\Domain\Model\Booking\CreateBookingModel;
use App\Domain\Model\Booking\UpdateBookingModel;
use App\Domain\Repository\BookingRepositoryInterface;
use App\Domain\Repository\TripRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\BookingService;
use App\Domain\Service\TripService;
use App\Domain\Service\UserService;
use App\Domain\ValueObject\UserRole;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class BookingServiceIntegrationTest extends TestCase
{
    private BookingService $bookingService;
    private TripService $tripService;
    private UserService $userService;
    private BookingRepositoryInterface $bookingRepository;
    private TripRepositoryInterface $tripRepository;
    private UserRepositoryInterface $userRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->bookingRepository = $this->createMock(BookingRepositoryInterface::class);
        $this->tripRepository = $this->createMock(TripRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->tripService = $this->createMock(TripService::class);

        $this->userService = new UserService($this->userRepository);

        $this->bookingService = new BookingService(
            $this->bookingRepository,
            $this->userService,
            $this->tripService
        );
    }

    public function testCompleteBookingProcess(): void
    {
        $driver = new User();
        $driver->setUsername('John Doe');
        $driver->setEmail('john@example.com');
        $driver->setPassword('password123');
        $driver->setRole(UserRole::ROLE_DRIVER->value);

        $passenger = new User();
        $passenger->setUsername('Jane Doe');
        $passenger->setEmail('jane@example.com');
        $passenger->setPassword('password123');
        $passenger->setRole(UserRole::ROLE_PASSENGER->value);

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

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime(new \DateTime('+1 day'));
        $trip->setAvailableSeats(4);
        $trip->setPricePerSeat('100.00');
        $trip->setStatus('planned');

        // Mock the trip ID
        $reflection = new \ReflectionClass($trip);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($trip, 1);

        $this->tripRepository->expects($this->never())
            ->method('findById');

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->with(2)
            ->willReturn($passenger);

        $this->tripService->expects($this->once())
            ->method('updateAvailableSeats')
            ->with(1, 2);

        $this->tripService->expects($this->exactly(2))
            ->method('findTripById')
            ->with(1)
            ->willReturn($trip);

        $model = new CreateBookingModel(
            trip: 1,
            passenger: 2,
            seatsBooked: 2,
            status: 'pending'
        );

        $this->bookingRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($booking) use ($trip, $passenger) {
                return $booking instanceof Booking
                    && $booking->getTrip() === $trip
                    && $booking->getPassenger() === $passenger
                    && 2 === $booking->getSeatsBooked()
                    && 'pending' === $booking->getStatus();
            }));

        $result = $this->bookingService->create($model);

        $this->assertEquals($trip, $result->getTrip());
        $this->assertEquals($passenger, $result->getPassenger());
        $this->assertEquals(2, $result->getSeatsBooked());
        $this->assertEquals('pending', $result->getStatus());
    }

    public function testBookingCancellationProcess(): void
    {
        $driver = new User();
        $driver->setUsername('John Doe');
        $driver->setEmail('john@example.com');
        $driver->setPassword('password123');
        $driver->setRole(UserRole::ROLE_DRIVER->value);

        $passenger = new User();
        $passenger->setUsername('Jane Doe');
        $passenger->setEmail('jane@example.com');
        $passenger->setPassword('password123');
        $passenger->setRole(UserRole::ROLE_PASSENGER->value);

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

        $trip = new Trip();
        $trip->setDriver($driver);
        $trip->setStartLocation($startLocation);
        $trip->setEndLocation($endLocation);
        $trip->setDepartureTime(new \DateTime('+1 day'));
        $trip->setAvailableSeats(4);
        $trip->setPricePerSeat('100.00');
        $trip->setStatus('planned');

        // Mock the trip ID
        $reflection = new \ReflectionClass($trip);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($trip, 1);

        $this->tripRepository->expects($this->never())
            ->method('findById');

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->with(2)
            ->willReturn($passenger);

        $this->tripService->expects($this->once())
            ->method('updateAvailableSeats')
            ->with(1, 2);

        $this->tripService->expects($this->exactly(2))
            ->method('findTripById')
            ->with(1)
            ->willReturn($trip);

        $model = new CreateBookingModel(
            trip: 1,
            passenger: 2,
            seatsBooked: 2,
            status: 'pending'
        );

        $this->bookingRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($booking) use ($trip, $passenger) {
                return $booking instanceof Booking
                    && $booking->getTrip() === $trip
                    && $booking->getPassenger() === $passenger
                    && 2 === $booking->getSeatsBooked()
                    && 'pending' === $booking->getStatus();
            }));

        $booking = $this->bookingService->create($model);

        // Mock the booking ID
        $reflection = new \ReflectionClass($booking);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($booking, 1);

        $this->bookingRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($booking);

        $this->bookingRepository->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($booking) {
                return 'cancelled' === $booking->getStatus();
            }));

        $updateModel = new UpdateBookingModel(
            id: $booking->getId(),
            trip: null,
            passenger: null,
            seats_booked: null,
            status: 'cancelled'
        );

        $this->bookingService->update($booking->getId(), $updateModel);
    }
}
