<?php

namespace App\Domain\Bus;

use App\Domain\DTO\UpdateStatisticsDTO;

interface UpdateStatisticsInterface
{
    public function sendUpdateStatisticsMessage(UpdateStatisticsDTO $dto): bool;
}
