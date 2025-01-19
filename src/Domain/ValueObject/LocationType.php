<?php

namespace App\Domain\ValueObject;

enum LocationType: string
{
    case CITY = 'city';
    case ADMINISTRATIVE_CENTER = 'administrative_center';
}
