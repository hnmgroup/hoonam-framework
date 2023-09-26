<?php

namespace Tests\Unit\Application;

use Hoonam\Framework\Application\Handler;
use PHPUnit\Framework\TestCase;

class HandlerTests extends TestCase
{
    public function test_multiple_handlers_dispatch_properly(): void
    {
        Handler::registerHandler('Event', 'Handler1');
        Handler::registerHandler('Event', 'Handler2');
        Handler::registerHandler('Event', 'Handler3');

        $handlers = Handler::getHandlerTypes('Event');

        $this->assertCount(3, $handlers);
    }
}
