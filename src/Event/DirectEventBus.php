<?php

declare(strict_types=1);

namespace Commander\Event;

final class DirectEventBus implements EventBus
{
    private EventHandlers $handlers;

    public function __construct(EventHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    public function dispatch(Events $events): void
    {
        foreach ($this->handlers as $eventHandler) {
            $eventHandler->handle($events);
        }
    }
}
