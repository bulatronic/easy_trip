<?php

namespace App\Controller\API\Review\Update;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/review/{id}', name: 'api_review_update', methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputReviewDTO $dto): OutputReviewDTO
    {
        return $this->manager->update($id, $dto);
    }
}
