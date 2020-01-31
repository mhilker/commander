<?php

declare(strict_types=1);

namespace Commander\Aggregate;

use Commander\Aggregate\Exception\AggregateNotFoundException;
use Commander\Aggregate\Exception\AggregateNotSavedException;
use Commander\Event\EventBus;
use Commander\Event\Events;
use Commander\EventStore\EventStore;
use Commander\EventStore\StorableEvents;
use Exception;

abstract class AbstractEventStoreAggregateRepository implements AggregateRepository
{
    private EventStore $eventStore;

    private EventBus $eventBus;

    public function __construct(EventStore $eventStore, EventBus $eventBus)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    public function save(AbstractAggregate $aggregate): void
    {
        try {
            $events = $aggregate->getEvents();
            $this->eventStore->store(StorableEvents::from($events));
        } catch (Exception $exception) {
            throw new AggregateNotSavedException('Could not save aggregate', 0, $exception);
        }

        $this->eventBus->dispatch($events);
    }

    public function load(AggregateId $id): AbstractAggregate
    {
        try {
            $events = $this->eventStore->load($id);
            $events = Events::from($events);
            return $this->createAggregateWithEvents($events);
        } catch (Exception $exception) {
            throw new AggregateNotFoundException('Could not load aggregate', 0, $exception);
        }
    }

    abstract protected function createAggregateWithEvents(Events $events): AbstractAggregate;
}
