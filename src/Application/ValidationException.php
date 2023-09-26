<?php

namespace Hoonam\Framework\Application;

use Throwable;
use Hoonam\Framework\Domain\ApplicationException;

class ValidationException extends ApplicationException
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        public readonly ?string $fieldName = null,
        array $data = [])
    {
        $data['fieldName'] = $fieldName;
        parent::__construct($message, $code, $previous, $data);
    }

    /**
     * @return array{string, mixed}
     */
    public function renderableContext(): array
    {
        return array_merge(parent::renderableContext(), [
            'fieldName' => $this->fieldName
        ]);
    }
}
