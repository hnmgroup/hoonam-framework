<?php

use Hoonam\Framework\Application\Handler;

function commandHandler(string $command, ?string $handler = null): string
{
    return Handler::registerHandler($command, $handler);
}

function queryHandler(string $query, ?string $handler = null): string
{
    return Handler::registerHandler($query, $handler);
}

function eventHandler(string $event, ?string $handler = null): string
{
    return Handler::registerHandler($event, $handler);
}
