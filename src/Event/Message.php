<?php

declare(strict_types=1);

namespace Commander\Event;

use Commander\ID\Identifier;
use Commander\ID\UUID;
use DateTimeImmutable;
use DateTimeZone;

final class Message
{
    private Identifier $id;
    private DateTimeImmutable $occurredOn;
    private Event $event;
    private Identifier $eventStreamId;
    private int $eventStreamVersion;

    private function __construct(
        Identifier $id,
        DateTimeImmutable $occurredOn,
        Event $event,
        Identifier $eventStreamId,
        int $eventStreamVersion
    ) {
        $this->id = $id;
        $this->occurredOn = $occurredOn;
        $this->event = $event;
        $this->eventStreamId = $eventStreamId;
        $this->eventStreamVersion = $eventStreamVersion;
    }

    public static function wrap(Identifier $eventStreamId, int $eventStreamVersion, Event $event): self
    {
        return new self(
            UUID::generateV4(),
            new DateTimeImmutable('now', new DateTimeZone('UTC')),
            $event,
            $eventStreamId,
            $eventStreamVersion,
        );
    }

    public static function reconstitute(array $data, Event $event): self
    {
        return new self(
            UUID::fromV4($data['event_id']),
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['occurred_on'], new DateTimeZone('UTC')),
            $event,
            UUID::fromV4($data['event_stream_id']),
            (int) $data['event_stream_version'],
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

    public function getEventStreamId(): Identifier
    {
        return $this->eventStreamId;
    }

    public function getEventStreamVersion(): int
    {
        return $this->eventStreamVersion;
    }
}
