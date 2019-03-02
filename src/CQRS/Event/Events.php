<?php

declare(strict_types=1);

namespace MHilker\CQRS\Event;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class Events implements IteratorAggregate
{
    private $events = [];

    private function __construct(iterable $events)
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public static function from(iterable $events = []): Events
    {
        return new self($events);
    }

    public function add(Event $event): void
    {
        $this->events[] = $event;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }
}
