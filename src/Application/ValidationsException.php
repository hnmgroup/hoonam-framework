<?php

namespace Hoonam\Framework\Application;

class ValidationsException extends ValidationException
{
    /**
     * @param ValidationException[] $errors
     */
    public function __construct(public readonly array $errors, string $message = '')
    {
        parent::__construct(
            message: $message,
            data: ['errors' => $errors],
        );
    }
}
