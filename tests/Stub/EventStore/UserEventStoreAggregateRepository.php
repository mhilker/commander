<?php

declare(strict_types=1);

namespace Commander\Stub\EventStore;

use Commander\Aggregate\AbstractEventStoreAggregateRepository;
use Commander\Event\Events;
use Commander\Stub\Aggregate\UserAggregate;

final class UserEventStoreAggregateRepository extends AbstractEventStoreAggregateRepository
{
    protected function createAggregateWithEvents(Events $events): UserAggregate
    {
        return UserAggregate::from($events);
    }
}
