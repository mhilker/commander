<?php

declare(strict_types=1);

namespace MHilker\EventSourcing;

use MHilker\EventSourcing\Event\Event;
use MHilker\EventSourcing\Event\Events;
use MHilker\EventSourcing\Exception\AggregateEventHandlerMethodNotFound;

abstract class AbstractAggregateRoot
{
    private $events;

    public function __construct()
    {
        $this->events = new Events();
    }

    /**
     * @param Event $event
     * @throws AggregateEventHandlerMethodNotFound
     * @return void
     */
    public function recordThat(Event $event): void
    {
        $this->events->addEvent($event);
        $this->apply($event);
    }

    /**
     * @param Events $events
     * @return AbstractAggregateRoot
     */
    public static function reconstituteFromHistory(Events $events): AbstractAggregateRoot
    {
        $aggregate = new static();

        foreach ($events as $event) {
            $aggregate->apply($event);
        }

        return $aggregate;
    }

    /**
     * @param object $event
     * @throws AggregateEventHandlerMethodNotFound
     * @return void
     */
    public function apply($event): void
    {
        $className = get_class($event);
        $parts = explode('\\', $className);
        $last = array_pop($parts);

        $method = 'when' . ucfirst(substr($last, 0, strlen('Event') * -1));

        if (method_exists($this, $method) === false) {
            throw new AggregateEventHandlerMethodNotFound();
        }

        $this->$method($event);
    }

    /**
     * @return AggregateId
     */
    abstract public function getAggregateId(): AggregateId;

    /**
     * @return Events
     */
    public function popEvents(): Events
    {
        $events = $this->events;
        $this->events = new Events();
        return $events;
    }
}
