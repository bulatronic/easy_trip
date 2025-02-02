<?php

namespace App\Controller\API\User\Delete;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[Route('/api/user/{id}', name: 'api_user_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        $this->manager->deleteUser($id);

        return $this->json(['message' => 'User deleted successfully']);
    }
}
