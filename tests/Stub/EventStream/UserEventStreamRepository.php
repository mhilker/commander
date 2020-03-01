<?php

declare(strict_types=1);

namespace Commander\Stub\EventStream;

use Commander\EventStream\AbstractEventStreamRepository;
use Commander\Event\Messages;

final class UserEventStreamRepository extends AbstractEventStreamRepository
{
    protected function createStreamWithMessages(Messages $messages): UserEventStream
    {
        return UserEventStream::from($messages);
    }
}
