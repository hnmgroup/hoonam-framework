<?php

namespace Hoonam\Framework\Domain;

use Throwable;
use Exception;
use ReflectionClass;

class ApplicationException extends Exception
{
    /** @type array{string, mixed} $data */
    public readonly array $data;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        array $data = [])
    {
        parent::__construct($message, $code, $previous);
        $this->data = array_merge(['className' => self::getClassName($this)], $data);
    }

    /**
     * @return array{string, mixed}
     */
    public function context(): array { return $this->data; }

    /**
     * @return array{string, mixed}
     */
    public function renderableContext(): array { return []; }

    public static function getClassName(Throwable $error): string
    {
        $className = (new ReflectionClass($error))->getShortName();
        $type = str_replace('Exception', '', $className);
        return !isEmpty($type) ? $type : 'Unknown';
    }
}
