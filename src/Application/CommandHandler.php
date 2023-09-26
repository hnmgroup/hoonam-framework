<?php

namespace Hoonam\Framework\Application;

/**
 * @template TCommand of Command<TResponse>
 * @template TResponse
 */
interface CommandHandler
{
    /**
     * @param TCommand $command
     * @return TResponse
     */
    public function handle($command);
}
