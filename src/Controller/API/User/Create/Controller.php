<?php

namespace App\Controller\API\User\Create;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/user', name: 'api_user_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputUserDTO $dto): OutputUserDTO
    {
        return $this->manager->create($dto);
    }
}
