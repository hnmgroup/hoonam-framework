<?php

namespace Hoonam\Framework\Application;

use Hoonam\Framework\Domain\Event;

interface EventBus
{
    public function dispatch(Event $event): void;
}
