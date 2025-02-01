<?php

namespace App\Domain\ValueObject;

enum BookingStatus: string
{
    case BOOKED = 'booked';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
}
