<?php

namespace App\Infrastructure\Bus\Adapter;

use App\Domain\Bus\UpdateStatisticsInterface;
use App\Domain\DTO\UpdateStatisticsDTO;
use App\Infrastructure\Bus\AmqpExchangeEnum;
use App\Infrastructure\Bus\RabbitMqBus;

readonly class UpdateStatisticsRabbitMqBus implements UpdateStatisticsInterface
{
    public function __construct(
        private RabbitMqBus $rabbitMqBus,
    ) {
    }

    public function sendUpdateStatisticsMessage(UpdateStatisticsDTO $dto): bool
    {
        return $this->rabbitMqBus->publishToExchange(AmqpExchangeEnum::UpdateStatistics, $dto);
    }
}
