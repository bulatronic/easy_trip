<?php

namespace App\Controller\CLI\Input;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateStatisticsDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(['daily', 'weekly', 'monthly', 'yearly'])]
        public string $periodType,

        #[Assert\NotBlank]
        #[Assert\Choice(['personal', 'global'])]
        public string $statisticsType,

        #[Assert\Type(\DateTimeInterface::class)]
        public \DateTimeInterface $startDate,

        #[Assert\Type(\DateTimeInterface::class)]
        public \DateTimeInterface $endDate,

        #[Assert\Type('int')]
        public ?int $driverId = null,
    ) {
    }
}
