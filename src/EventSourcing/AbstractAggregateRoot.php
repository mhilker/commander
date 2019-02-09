<?php

declare(strict_types=1);

namespace MHilker\EventSourcing;

abstract class AbstractAggregateRoot
{
    private $events;

    public function __construct()
    {
        $this->events = new Events();
    }

    public function recordThat(Event $event): void
    {
        $this->events->addEvent($event);
        $this->apply($event);
    }

    public static function reconstituteFromHistory(Events $events): AbstractAggregateRoot
    {
        $aggregate = new static();

        foreach ($events as $event) {
            $aggregate->apply($event);
        }

        return $aggregate;
    }

    public function apply($event): void
    {
        $className = get_class($event);
        $parts = explode('\\', $className);
        $last = array_pop($parts);

        $method = 'when' . ucfirst(substr($last, 0, strlen('Event') * -1));

        $this->$method($event);
    }

    abstract public function getAggregateId(): AggregateId;

    public function getEvents(): Events
    {
        $events = $this->events;
        $this->events = new Events();
        return $events;
    }
}
