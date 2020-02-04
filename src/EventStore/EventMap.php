<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Message;

interface EventMap
{
    public function reconstitute(array $data): Message;
}
