<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

use MHilker\CQRS\Event\Exception\DuplicateEventHandlerException;
use MHilker\CQRS\Event\Exception\InvalidEventClassException;

class EventHandlers
{
    private $handlers = [];

    /**
     * @param callable $eventHandler
     * @param string $eventClass
     * @return void
     * @throws DuplicateEventHandlerException
     */
    public function addHandler(callable $eventHandler, string $eventClass): void
    {
        if (class_exists($eventClass) === false) {
            throw new InvalidEventClassException();
        }

        if (in_array($eventHandler, $this->handlers[$eventClass]) === true) {
            throw new DuplicateEventHandlerException();
        }

        $this->handlers[$eventClass][] = $eventHandler;
    }

    /**
     * @param string $eventClass
     * @return array
     */
    public function getEventHandlersForEventClass(string $eventClass): array
    {
        return $this->handlers[$eventClass] ?? [];
    }
}
