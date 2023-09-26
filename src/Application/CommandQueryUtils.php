<?php

namespace Hoonam\Framework\Application;

use Hoonam\Framework\Utilities\Core;
use Hoonam\Framework\Utilities\Str;

trait CommandQueryUtils
{
    /** @type ValidationException[] $_errors */
    private array $_errors = [];

    public function sanitize(): void
    {
        $this->sanitizeStrings();
    }

    public function validate(): void
    {
    }

    protected final function sanitizeStrings(): void
    {
        Core::manipulate($this, function ($value) {
            return is_string($value) ? Str::sanitizeText($value) : $value;
        });
    }

    public final function addError(ValidationException $exception): void
    {
        $this->_errors[] = $exception;
    }

    /**
     * @return ValidationException[]
     */
    public final function errors(): array { return $this->_errors; }

    public final function hasError(): bool { return count($this->_errors) > 0; }

    public final function throwValidationErrors(): void
    {
        if (!$this->hasError()) return;
        $errors = $this->errors();
        if (count($errors) == 1) throw $errors[0];
        else throw new ValidationsException($errors, 'validation errors');
    }
}
