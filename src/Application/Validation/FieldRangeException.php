<?php

namespace Hoonam\Framework\Application\Validation;

use Hoonam\Framework\Application\ValidationException;

class FieldRangeException extends ValidationException
{
    public function __construct(string $name, string $message = '')
    {
        parent::__construct($message, fieldName: $name);
    }
}
