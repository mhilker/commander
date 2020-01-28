<?php

declare(strict_types=1);

namespace Commander\EventStore;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class StorableEvents implements IteratorAggregate
{
    private array $events = [];

    public function __construct(iterable $events)
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public static function from(iterable $events = []): StorableEvents
    {
        return new self($events);
    }

    public function add(StorableEvent $event): void
    {
        $this->events[] = $event;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }
}
