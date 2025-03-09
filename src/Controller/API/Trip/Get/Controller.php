<?php

namespace App\Controller\API\Trip\Get;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/trip/{id}', name: 'api_trip_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputTripDTO
    {
        return $this->manager->get($id);
    }
}
