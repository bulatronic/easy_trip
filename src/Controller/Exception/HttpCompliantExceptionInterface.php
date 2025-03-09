<?php

namespace App\Controller\Exception;

interface HttpCompliantExceptionInterface
{
    public function getHttpResponseBody(): string;

    public function getHttpCode(): int;
}
