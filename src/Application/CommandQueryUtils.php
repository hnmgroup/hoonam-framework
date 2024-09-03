<?php

namespace Hoonam\Framework\Application;

trait CommandQueryUtils
{
    /** @type ValidationException[] $_errors */
    private array $_errors = [];

    public function sanitize(): void
    {
    }

    public function validate(): void
    {
    }

    final public function addError(ValidationException $exception): void
    {
        $this->_errors[] = $exception;
    }

    /**
     * @return ValidationException[]
     */
    final public function errors(): array { return $this->_errors; }

    final public function hasError(): bool { return count($this->_errors) > 0; }

    final public function throwValidationErrors(): void
    {
        if (!$this->hasError()) return;
        $errors = $this->errors();
        if (count($errors) == 1) throw $errors[0];
        else throw new ValidationsException($errors, 'validation errors');
    }
}
