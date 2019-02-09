<?php

declare(strict_types=1);

namespace MHilker\EventSourcing;

use ArrayIterator;
use IteratorAggregate;

class Events implements IteratorAggregate
{
    private $events = [];

    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->events);
    }
}
