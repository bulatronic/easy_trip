<?php

namespace App\Controller\API\Location\Update;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/location/{id}', name: 'api_location_update', methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputLocationDTO $dto): OutputLocationDTO
    {
        return $this->manager->update($id, $dto);
    }
}
