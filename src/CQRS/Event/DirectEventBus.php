<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

use MHilker\EventSourcing\Event;

class DirectEventBus implements EventBusInterface
{
    private $handlers;

    public function __construct(EventHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    public function trigger(Event $event): void
    {
        $eventClass = get_class($event);

        $eventHandlers = $this->handlers->getEventHandlersForEventClass($eventClass);

        foreach ($eventHandlers as $eventHandler) {
            $eventHandler($event);
        }
    }
}
