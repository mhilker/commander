<?php

declare(strict_types=1);

namespace Commander\Aggregate;

use Commander\Event\Events;
use Commander\EventStore\StorableEvent;

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

    public static function from(Events $events): AbstractAggregate
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
