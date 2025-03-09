<?php

namespace App\Domain\Model\Location;

class LocationModel
{
    public function __construct(
        public string $name,
        public string $type,
        public string $latitude,
        public string $longitude,
    ) {
    }
}
