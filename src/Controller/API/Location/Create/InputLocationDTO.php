<?php

namespace App\Controller\API\Location\Create;

use Symfony\Component\Validator\Constraints as Assert;

class InputLocationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Length(min: 2, max: 10)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Choice(['city', 'administrative_center'])]
        public string $type,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Type(type: 'numeric', message: 'Широта должна быть числом.')]
        #[Assert\Range(
            notInRangeMessage: 'Широта должна быть в диапазоне от -90 до 90.',
            min: -90,
            max: 90
        )]
        public string $latitude,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Type(type: 'numeric', message: 'Долгота должна быть числом.')]
        #[Assert\Range(
            notInRangeMessage: 'Долгота должна быть в диапазоне от -180 до 180.',
            min: -180,
            max: 180
        )]
        public string $longitude,
    ) {
    }
}
