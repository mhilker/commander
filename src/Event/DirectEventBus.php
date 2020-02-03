<?php

declare(strict_types=1);

namespace Commander\Event;

final class DirectEventBus implements EventDispatcher
{
    private EventHandlers $handlers;
    private EventPublisher $publisher;

    public function __construct(EventHandlers $handlers, EventPublisher $publisher)
    {
        $this->handlers = $handlers;
        $this->publisher = $publisher;
    }

    public function dispatch(): void
    {
        while ($this->publisher->count() > 0) {
            $events = $this->publisher->dequeue();
            foreach ($events as $event) {
                foreach ($this->handlers as $handler) {
                    $handler->handle($event);
                }
            }
        }
    }
}
