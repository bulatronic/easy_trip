<?php

namespace App\Controller\API\Booking\Get;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[OA\Tag(name: 'Booking')]
    #[OA\Get(
        description: 'Retrieves a booking by ID',
        summary: 'Get a booking by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the booking to retrieve'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking retrieved successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputBookingDTO::class))
            ),
            new OA\Response(
                response: 404,
                description: 'Booking not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_USER->value])]
    #[Route('/api/booking/{id}', name: 'api_booking_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputBookingDTO
    {
        return $this->manager->get($id);
    }
}
