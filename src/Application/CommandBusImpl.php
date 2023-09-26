<?php

namespace Hoonam\Framework\Application;

use Exception;
use Throwable;
use ReflectionClass;
use Illuminate\Contracts\Container\Container;

class CommandBusImpl implements CommandBus
{
    public function __construct(private readonly Container $container)
    {
    }

    public function handle(Command $command)
    {
        $command->sanitize();

        $command->validate();
        $command->throwValidationErrors();

        $handlerType = $this->getHandlerType($command);

        if (!$this->isTransactional($handlerType)) {
            $handler = $this->resolveHandler($handlerType);
            return $handler->handle($command);
        }

        /** @type UnitOfWork $unitOfWork */
        $unitOfWork = $this->container->make(UnitOfWork::class);
        $unitOfWork->begin();
        try {
            $handler = $this->resolveHandler($handlerType);
            $response = $handler->handle($command);
            $unitOfWork->commit();
            return $response;
        } catch (Throwable $error) {
            $unitOfWork->rollback();
            throw $error;
        }
    }

    private function getHandlerType(Command $command): string
    {
        $commandType = get_class($command);
        $handlerType = Handler::getHandlerTypes($commandType)[0] ?? null;

        if (is_null($handlerType))
            throw new Exception("no handler registered for '$commandType'");

        return $handlerType;
    }

    private function resolveHandler(string $handlerType): CommandHandler
    {
        return $this->container->make($handlerType);
    }

    private function isTransactional(string $handlerType): bool
    {
        return count((new ReflectionClass($handlerType))->getAttributes(Transactional::class)) > 0;
    }
}
