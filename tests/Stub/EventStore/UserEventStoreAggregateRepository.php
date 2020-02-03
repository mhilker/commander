<?php

declare(strict_types=1);

namespace Commander\Stub\EventStore;

use Commander\Aggregate\AbstractEventStoreAggregateRepository;
use Commander\Event\Messages;
use Commander\Stub\Aggregate\UserAggregate;

final class UserEventStoreAggregateRepository extends AbstractEventStoreAggregateRepository
{
    protected function createAggregateWithMessages(Messages $messages): UserAggregate
    {
        return UserAggregate::from($messages->getEvents());
    }
}
