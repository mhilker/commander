<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Aggregate\AggregateId;
use Commander\Event\Event;
use Commander\EventStore\StorableEvent;
use Commander\Stub\Aggregate\UserName;
use Commander\UUID;
use DateTimeImmutable;
use DateTimeZone;

final class UserDisabledEvent implements Event, StorableEvent
{
    public const TOPIC = 'com.example.event.user_registered';

    private UUID $id;

    private AggregateId $aggregateId;

    private DateTimeImmutable $occurredOn;

    public function __construct(AggregateId $aggregateId, DateTimeImmutable $occurredOn)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredOn = $occurredOn;
    }

    public static function occur(AggregateId $id): self
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        return new self($id, $now);
    }

    public static function restore(array $event): StorableEvent
    {
        $id = UserId::from($event['aggregate_id']);
        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $event['occurred_on'], new DateTimeZone('UTC'));

        return new self($id, $now);
    }

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getPayload(): array
    {
        return [];
    }
}
