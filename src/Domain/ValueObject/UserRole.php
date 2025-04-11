<?php

namespace App\Domain\ValueObject;

enum UserRole: string
{
    case ROLE_PASSENGER = 'ROLE_PASSENGER';
    case ROLE_DRIVER = 'ROLE_DRIVER';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_USER = 'ROLE_USER';
}
