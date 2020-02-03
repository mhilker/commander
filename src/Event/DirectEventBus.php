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
            $messages = $this->publisher->dequeue();
            $this->dispatchMessages($messages);
        }
    }

    private function dispatchMessages(Messages $messages): void
    {
        foreach ($messages as $message) {
            $event = $message->getEvent();
            foreach ($this->handlers as $handler) {
                $handler->handle($event);
            }
        }
    }
}
