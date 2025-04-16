<?php

namespace App\Controller\API\User\PersonalStatistic;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/statistics/driver', name : 'api_statistics_driver', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputStatisticDTO $dto): OutputStatisticDTO
    {
        return $this->manager->get($dto);
    }
}
