<?php

namespace App\Controller\API\Booking\Create;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/booking', name: 'api_booking_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputBookingDTO $dto): OutputBookingDTO
    {
        return $this->manager->create($dto);
    }
}
