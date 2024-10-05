<?php

namespace Hoonam\Framework\Application;

/**
 * @template TResponse
 * @template TCommand of Command<TResponse>
 */
interface CommandHandler
{
    /**
     * @param TCommand $command
     * @return TResponse
     */
    public function handle($command);
}
