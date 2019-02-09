<?php

declare(strict_types=1);

namespace MHilker\EventSourcing\Event;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Events implements IteratorAggregate
{
    private $events = [];

    /**
     * @param Event $event
     * @return void
     */
    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }
}
