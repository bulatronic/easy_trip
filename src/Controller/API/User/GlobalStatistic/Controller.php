<?php

namespace App\Controller\API\User\GlobalStatistic;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/statistics/global', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputStatisticDTO $dto): OutputStatisticDTO
    {
        return $this->manager->get($dto);
    }
}
