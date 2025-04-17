<?php

namespace App\Controller\API\User\Update;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[OA\Tag(name: 'User')]
    #[OA\Put(
        description: 'Updates a user by ID',
        summary: 'Update a user by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the user to update'),
        ],
        requestBody: new OA\RequestBody(
            description: 'User data',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: InputUserDTO::class))
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputUserDTO::class))
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'type', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Error message'),
                    ]
                )
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_USER->value])]
    #[Route('/api/user/{id}', name: 'api_user_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputUserDTO $dto): OutputUserDTO
    {
        return $this->manager->update($id, $dto);
    }
}
