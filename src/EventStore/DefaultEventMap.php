<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Message;
use Commander\EventStore\Exception\EventMapException;

final class DefaultEventMap implements EventMap
{
    private array $map = [];

    /**
     * @throws EventMapException
     */
    public function __construct(array $map)
    {
        foreach ($map as $topic => $events) {
            foreach ($events as $version => $class) {
                $this->add($topic, $version, $class);
            }
        }
    }

    /**
     * @throws EventMapException
     */
    private function add(string $topic, int $version, string $class): void
    {
        if (!class_exists($class)) {
            throw new EventMapException(sprintf('Class "%s" does not exist', $class));
        }

        $this->map[$topic][$version] = $class;
    }

    /**
     * @throws EventMapException
     */
    public function reconstitute(array $data): Message
    {
        $class = $this->determineEventClass($data);
        $payload = json_decode($data['payload'], true, 512, JSON_THROW_ON_ERROR);
        $event = $class::fromPayload($payload);

        return Message::reconstitute($data, $event);
    }

    /**
     * @throws EventMapException
     */
    private function determineEventClass(array $data): string
    {
        $topic   = $data['topic'];
        $version = $data['version'];

        if (!isset($this->map[$topic][$version])) {
            throw new EventMapException(sprintf(
                'No event class configured for topic "%s" of version %d.',
                $topic,
                $version
            ));
        }

        $class = $this->map[$topic][$version];

        // TODO: check if method exists

        return $class;
    }
}
