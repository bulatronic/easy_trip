<?php

namespace App\Controller\API\Trip\Create;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/trip', name: 'api_trip_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputTripDTO $dto): OutputTripDTO
    {
        return $this->manager->create($dto);
    }
}
