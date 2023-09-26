<?php

namespace Hoonam\Framework\Application;

interface CommandBus
{
    /**
     * @template TResponse
     * @param Command<TResponse> $command
     * @return TResponse
     */
    public function handle(Command $command);
}
