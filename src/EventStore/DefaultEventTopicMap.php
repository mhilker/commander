<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Message;

final class DefaultEventTopicMap implements EventTopicMap
{
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function reconstitute(array $data): Message
    {
        $topic = $data['topic'];
        $class = $this->map[$topic];
        $payload = json_decode($data['payload'], true, 512, JSON_THROW_ON_ERROR);
        $event = $class::fromPayload($payload);

        return Message::reconstitute($data, $event);
    }
}
