<?php

declare(strict_types=1);

namespace MHilker\CQRS\Aggregate;

use MHilker\CQRS\EventStore\StorableEvent;
use MHilker\CQRS\Event\Events;

abstract class AbstractAggregate
{
    private $events = [];

    protected function __construct(?Events $events)
    {
        if ($events !== null) {
            foreach ($events as $event) {
                $this->apply($event);
            }
        }
    }

    public static function from(Events $events)
    {
        return new static($events);
    }

    public function record(StorableEvent $event): void
    {
        $this->apply($event);
        $this->events[] = $event;
    }

    public function getEvents(): Events
    {
        return Events::from($this->events);
    }

    abstract protected function apply(StorableEvent $event): void;

    abstract public function getAggregateId(): AggregateId;
}
