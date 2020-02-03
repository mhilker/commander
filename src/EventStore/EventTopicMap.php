<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Message;

final class EventTopicMap
{
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function restore(array $data): Message
    {
        $topic = $data['topic'];
        $class = $this->map[$topic];
        $payload = json_decode($data['payload'], true, 512, JSON_THROW_ON_ERROR);
        $event = $class::restore($payload);

        return Message::reconstitute($data, $event);
    }
}
