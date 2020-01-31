<?php

declare(strict_types=1);

namespace Commander\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class Events implements IteratorAggregate, Countable
{
    private array $events = [];

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

    /**
     * @return Traversable | Event[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }
}
