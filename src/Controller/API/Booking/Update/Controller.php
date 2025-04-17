<?php

namespace App\Controller\API\Booking\Update;

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

    #[OA\Tag(name: 'Booking')]
    #[OA\Put(
        description: 'Updates a booking by ID',
        summary: 'Update a booking by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the booking to update'),
        ],
        requestBody: new OA\RequestBody(
            description: 'Booking data',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: InputBookingDTO::class))
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking updated successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputBookingDTO::class))
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
    #[RequireRole(roles: [UserRole::ROLE_PASSENGER->value])]
    #[Route('/api/booking/{id}', name: 'api_booking_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputBookingDTO $dto): OutputBookingDTO
    {
        return $this->manager->update($id, $dto);
    }
}
