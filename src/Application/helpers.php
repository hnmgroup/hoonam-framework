<?php

use Hoonam\Framework\Application\Command;
use Hoonam\Framework\Application\Query;
use Hoonam\Framework\Domain\Event;
use Hoonam\Framework\Application\CommandHandler;
use Hoonam\Framework\Application\QueryHandler;
use Hoonam\Framework\Application\EventHandler;
use Hoonam\Framework\Application\Handler;

/**
 * @template TResponse
 * @template TCommand of Command<TResponse>
 * @template THandler of CommandHandler<TCommand>
 * @param class-string<TCommand> $command
 * @param ?class-string<THandler> $handler
 * @return class-string<THandler>
 */
function commandHandler(string $command, ?string $handler = null): string
{
    return Handler::registerHandler($command, $handler);
}

/**
 * @template TResult
 * @template TQuery of Query<TResult>
 * @template THandler of QueryHandler<TQuery>
 * @param class-string<TQuery> $query
 * @param ?class-string<THandler> $handler
 * @return class-string<THandler>
 */
function queryHandler(string $query, ?string $handler = null): string
{
    return Handler::registerHandler($query, $handler);
}

/**
 * @template TEvent of Event
 * @template THandler of EventHandler<TEvent>
 * @param class-string<TEvent> $event
 * @param ?class-string<THandler> $handler
 * @return class-string<THandler>
 */
function eventHandler(string $event, ?string $handler = null): string
{
    return Handler::registerHandler($event, $handler);
}
