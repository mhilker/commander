<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Message;
use Commander\EventStore\Exception\EventMapException;

interface EventMap
{
    /**
     * @throws EventMapException
     */
    public function reconstitute(array $data): Message;
}
