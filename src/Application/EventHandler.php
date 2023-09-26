<?php

namespace Hoonam\Framework\Application;

use Hoonam\Framework\Domain\Event;

/**
 * @template TEvent of Event
 */
interface EventHandler
{
    /**
     * @param TEvent $event
     */
    public function handle($event): void;
}
