<?php

namespace Hoonam\Framework\Application;

use Illuminate\Support\Collection;

class Handler
{
    /** @type array{string, Collection<string>} $_handlers */
    private static array $_handlers = [];

    public static function registerHandler(string $subject, ?string $handler = null): string
    {
        $handler ??= $subject.'Handler';

        $handlers = self::$_handlers[$subject] ?? null;
        is_null($handlers)
            ? self::$_handlers[$subject] = collect([$handler])
            : $handlers->add($handler);

        return $handler;
    }

    /**
     * @return string[]
     */
    public static function getHandlerTypes(string $subject): array
    {
        return (self::$_handlers[$subject] ?? Collection::empty())->all();
    }
}
