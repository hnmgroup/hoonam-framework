<?php

namespace Hoonam\Framework\Utilities;

use Symfony\Component\HttpFoundation\Response;

class Web
{
    public static function contentStatus(mixed $value): int
    {
        return isset($value) ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
    }
}
