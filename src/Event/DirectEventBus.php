<?php

declare(strict_types=1);

namespace Commander\Event;

use Commander\EventStore\EventContext;

final class DirectEventBus implements EventDispatcher
{
    private EventHandlers $handlers;
    private EventContext $context;
    private EventPublisher $publisher;

    public function __construct(EventHandlers $handlers, EventContext $context, EventPublisher $publisher)
    {
        $this->handlers = $handlers;
        $this->context = $context;
        $this->publisher = $publisher;
    }

    public function dispatch(): void
    {
        while (!$this->publisher->isEmpty()) {
            $messages = $this->publisher->dequeue();
            $this->dispatchMessages($messages);
        }
    }

    private function dispatchMessages(Messages $messages): void
    {
        foreach ($messages as $message) {
            $this->context->setCurrentCausationId($message->getId());
            $event = $message->getEvent();
            foreach ($this->handlers as $handler) {
                $handler->handle($event);
            }
        }
    }
}
