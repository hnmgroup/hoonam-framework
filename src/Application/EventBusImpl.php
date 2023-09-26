<?php

namespace Hoonam\Framework\Application;

use Hoonam\Framework\Domain\Event;
use Illuminate\Contracts\Container\Container;

class EventBusImpl implements EventBus
{
    public function __construct(private readonly Container $container)
    {
    }

    public function dispatch(Event $event): void
    {
        $handlerTypes = $this->getHandlerTypes($event);

        foreach ($handlerTypes as $handlerType) {
            $handler = $this->resolveHandler($handlerType);
            $handler->handle($event);
        }
    }

    /**
     * @return string[]
     */
    private function getHandlerTypes(Event $event): array
    {
        return Handler::getHandlerTypes(get_class($event));
    }

    private function resolveHandler(string $handlerType): EventHandler
    {
        return $this->container->make($handlerType);
    }
}
