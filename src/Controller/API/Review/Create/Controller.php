<?php

namespace App\Controller\API\Review\Create;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/review', name: 'api_review_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputReviewDTO $dto): OutputReviewDTO
    {
        return $this->manager->create($dto);
    }
}
