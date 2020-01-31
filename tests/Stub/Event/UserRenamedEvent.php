<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Aggregate\AggregateId;
use Commander\Event\Event;
use Commander\EventStore\StorableEvent;
use Commander\Stub\Aggregate\UserId;
use DateTimeImmutable;
use DateTimeZone;

class UserRenamedEvent implements Event, StorableEvent
{
    public const TOPIC = 'com.example.event.user_renamed';

    private AggregateId $aggregateId;

    private DateTimeImmutable $occurredOn;

    private string $name;

    private function __construct(AggregateId $aggregateId, DateTimeImmutable $occurredOn, string $name)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredOn = $occurredOn;
        $this->name = $name;
    }

    public static function occur(AggregateId $id, string $name): self
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        return new UserRenamedEvent($id, $now, $name);
    }

    public static function restore(array $event): StorableEvent
    {
        $id = UserId::from($event['aggregate_id']);
        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $event['occurred_on'], new DateTimeZone('UTC'));
        $name = $event['payload']['name'];
        return new UserRenamedEvent($id, $now, $name);
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getPayload(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }
}
