<?php

namespace App\Controller\Security;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RequireRole
{
    public function __construct(
        public array $roles = [],
    ) {
    }
}
