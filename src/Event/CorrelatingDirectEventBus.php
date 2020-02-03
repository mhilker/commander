<?php

declare(strict_types=1);

namespace Commander\Event;

use Commander\EventStore\CorrelatingEventStore;

final class CorrelatingDirectEventBus implements EventDispatcher
{
    private EventHandlers $handlers;
    private CorrelatingEventStore $eventStore;
    private SplQueueEventPublisher $publisher;

    public function __construct(EventHandlers $handlers, CorrelatingEventStore $eventStore, SplQueueEventPublisher $publisher)
    {
        $this->handlers = $handlers;
        $this->eventStore = $eventStore;
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
            $this->eventStore->useCausationId($message->getId());
            $event = $message->getEvent();
            foreach ($this->handlers as $handler) {
                $handler->handle($event);
            }
        }
    }
}
