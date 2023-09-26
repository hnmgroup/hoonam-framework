<?php

namespace Hoonam\Framework\Application;

use Exception;
use Illuminate\Contracts\Container\Container;

class QueryBusImpl implements QueryBus
{
    public function __construct(private readonly Container $container)
    {
    }

    public function handle(Query $query)
    {
        $query->sanitize();

        $query->validate();
        $query->throwValidationErrors();

        $handler = $this->resolveHandler($query);

        return $handler->handle($query);
    }

    private function resolveHandler(Query $query): QueryHandler
    {
        $queryType = get_class($query);
        $handlerType = Handler::getHandlerTypes($queryType)[0] ?? null;

        if (is_null($handlerType))
            throw new Exception("no handler registered for '$queryType'");

        return $this->container->make($handlerType);
    }
}
