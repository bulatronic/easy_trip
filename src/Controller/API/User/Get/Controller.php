<?php

namespace App\Controller\API\User\Get;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/user/{id}', name: 'api_user_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputUserDTO
    {
        return $this->manager->get($id);
    }
}
