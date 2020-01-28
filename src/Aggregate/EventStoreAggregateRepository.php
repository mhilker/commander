<?php

declare(strict_types=1);

namespace Commander\Aggregate;

use Commander\Aggregate\Exception\AggregateNotFoundException;
use Commander\Aggregate\Exception\AggregateNotSavedException;
use Commander\Event\EventBus;
use Commander\EventStore\EventStore;
use Commander\EventStore\StorableEvents;

final class EventStoreAggregateRepository implements AggregateRepository
{
    /** @var EventStore */
    private $eventStore;

    /** @var EventBus */
    private $eventBus;

    /** @var string */
    private $aggregateClass;

    public function __construct(EventStore $eventStore, EventBus $eventBus, string $aggregateClass)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
        $this->aggregateClass = $aggregateClass;
    }

    public function save(AbstractAggregate $aggregate): void
    {
        try {
            $events = $aggregate->getEvents();
            $this->eventStore->store(StorableEvents::from($events));
        } catch (\Exception $exception) {
            throw new AggregateNotSavedException('Could not save aggregate', 0, $exception);
        }

        $this->eventBus->dispatch($events);
    }

    public function load(AggregateId $id): AbstractAggregate
    {
        try {
            $events = $this->eventStore->load($id);
            return $this->aggregateClass::from($events);
        } catch (\Exception $exception) {
            throw new AggregateNotFoundException('Could not load aggregate', 0, $exception);
        }
    }
}
