<?php

namespace Hoonam\Framework\Domain;

use Hoonam\Framework\Application\EventBus;

abstract class AggregateRoot extends AuditableEntity
{
    /** @type Event[] */
    private array $_events;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->_events = [];
    }

    /**
     * @return Event[]
     */
    protected function events(): array { return array_values($this->_events); }

    protected function appendEvent(Event $event): void
    {
        $this->_events[] = $event;
    }

    protected function clearEvents(): void
    {
        $this->_events = [];
    }

    private function dispatchEvents(): void
    {
        if (count($this->_events) == 0) return;

        /** @type EventBus $eventBus */
        $eventBus = app()->make(EventBus::class);
        $events = $this->events();
        $this->clearEvents();
        foreach ($events as $event) {
            $eventBus->dispatch($event);
        }
    }

    protected function afterCreated(): void
    {
        parent::afterCreated();
        $this->dispatchEvents();
    }

    protected function afterUpdated(): void
    {
        parent::afterUpdated();
        $this->dispatchEvents();
    }

    protected function afterDeleted(): void
    {
        parent::afterDeleted();
        $this->dispatchEvents();
    }
}
