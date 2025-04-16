<?php

namespace App\Infrastructure\Bus;

enum AmqpExchangeEnum: string
{
    case UpdateStatistics = 'update_statistics';
}
