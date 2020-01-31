<?php

declare(strict_types=1);

namespace Commander\EventStore;

final class EventTopicMap
{
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function restore(array $row): StorableEvent
    {
        $topic = $row['topic'];
        $class = $this->map[$topic];
        return $class::restore($row);
    }
}
