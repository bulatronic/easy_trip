<?php

namespace App\Controller\API\User\GlobalStatistic;

use Symfony\Component\Validator\Constraints as Assert;

class InputStatisticDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(['daily', 'weekly', 'monthly', 'yearly'])]
        public string $periodType,
    ) {
    }
}
