<?php

declare(strict_types=1);

namespace Commander\Event;

use Commander\Identifier;
use Commander\UUID;
use DateTimeImmutable;
use DateTimeZone;

final class Message
{
    private Identifier $id;
    private DateTimeImmutable $occurredOn;
    private Event $event;
    private Identifier $aggregateId;
    private int $aggregateVersion;

    private function __construct(
        Identifier $id,
        DateTimeImmutable $occurredOn,
        Event $event,
        Identifier $aggregateId,
        int $aggregateVersion
    ) {
        $this->id = $id;
        $this->occurredOn = $occurredOn;
        $this->event = $event;
        $this->aggregateId = $aggregateId;
        $this->aggregateVersion = $aggregateVersion;
    }

    public static function wrap(Identifier $aggregateId, int $aggregateVersion, Event $event): self
    {
        return new self(
            UUID::generate(),
            new DateTimeImmutable('now', new DateTimeZone('UTC')),
            $event,
            $aggregateId,
            $aggregateVersion,
        );
    }

    public static function reconstitute(array $data, Event $event): self
    {
        return new self(
            UUID::from($data['event_id']),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['occurred_on'], new DateTimeZone('UTC')),
            $event,
            UUID::from($data['aggregate_id']),
            (int) $data['aggregate_version'],
        );
    }

    public function getId(): Identifier
    {
        return $this->id;
    }

    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getAggregateId(): Identifier
    {
        return $this->aggregateId;
    }

    public function getAggregateVersion(): int
    {
        return $this->aggregateVersion;
    }
}
