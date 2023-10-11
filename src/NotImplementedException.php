<?php

namespace Hoonam\Framework;

use Exception;
use Throwable;

class NotImplementedException extends Exception
{
    public function __construct(
        ?string $message = null,
        int $code = 0,
        ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'the function not implemented', $code, $previous);
    }
}
