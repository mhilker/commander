<?php

declare(strict_types=1);

namespace Commander\Stub\EventStore;

use Commander\Aggregate\AbstractEventStoreAggregateRepository;
use Commander\Event\Events;
use Commander\Stub\Aggregate\TestAggregate;

final class TestEventStoreAggregateRepository extends AbstractEventStoreAggregateRepository
{
    protected function createAggregateWithEvents(Events $events): TestAggregate
    {
        return TestAggregate::from($events);
    }
}
