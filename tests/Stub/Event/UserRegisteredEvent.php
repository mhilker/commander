<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Aggregate\AggregateId;
use Commander\Event\Event;
use Commander\EventStore\StorableEvent;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;
use DateTimeImmutable;
use DateTimeZone;

class UserRegisteredEvent implements Event, StorableEvent
{
    public const TOPIC = 'com.example.event.user_registered';

    private AggregateId $aggregateId;

    private DateTimeImmutable $occurredOn;

    private UserName $name;

    private function __construct(AggregateId $aggregateId, DateTimeImmutable $occurredOn, UserName $name)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredOn = $occurredOn;
        $this->name = $name;
    }

    public static function occur(AggregateId $id, UserName $name): self
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        return new UserRegisteredEvent($id, $now, $name);
    }

    public static function restore(array $event): StorableEvent
    {
        $payload = json_decode($event['payload'], true, 512, JSON_THROW_ON_ERROR);

        $id = UserId::from($event['aggregate_id']);
        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $event['occurred_on'], new DateTimeZone('UTC'));
        $name = UserName::from($payload['name']);

        return new UserRegisteredEvent($id, $now, $name);
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
            'name' => $this->name->asString(),
        ];
    }

    public function getName(): UserName
    {
        return $this->name;
    }
}
