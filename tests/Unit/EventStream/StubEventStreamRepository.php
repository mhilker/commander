<?php

declare(strict_types=1);

namespace Commander\Unit\EventStream;

use Commander\Event\Messages;
use Commander\EventStream\AbstractEventStream;
use Commander\EventStream\AbstractEventStreamRepository;

final class StubEventStreamRepository extends AbstractEventStreamRepository
{
    protected function createStreamWithMessages(Messages $messages): AbstractEventStream
    {
        return StubEventStream::from($messages);
    }
}
