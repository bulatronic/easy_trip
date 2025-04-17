<?php

namespace App\Controller\API\User\PersonalStatistic;

use Symfony\Component\Validator\Constraints as Assert;

class InputStatisticDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        public int $driverId,

        #[Assert\NotBlank]
        #[Assert\Choice(['daily', 'weekly', 'monthly', 'yearly'])]
        public string $periodType,
    ) {
    }
}
