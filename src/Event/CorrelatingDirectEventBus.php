<?php

declare(strict_types=1);

namespace Commander\Event;

use Commander\EventStore\CorrelatingEventStore;
use SplQueue;

final class CorrelatingDirectEventBus implements EventDispatcher, EventPublisher
{
    private EventHandlers $handlers;

    private CorrelatingEventStore $eventStore;

    /** @var SplQueue | Events[] */
    private SplQueue $queue;

    public function __construct(EventHandlers $handlers, CorrelatingEventStore $eventStore)
    {
        $this->handlers = $handlers;
        $this->eventStore = $eventStore;
        $this->queue = new SplQueue();
    }

    public function publish(Events $events): void
    {
        $this->queue->enqueue($events);
    }

    public function dispatch(): void
    {
        foreach ($this->handlers as $eventHandler) {
            $events = $this->queue->dequeue();
            foreach ($events as $event) {
                $this->eventStore->useCausationId($event->getId());
                $eventHandler->handle($event);
            }
        }
    }
}
