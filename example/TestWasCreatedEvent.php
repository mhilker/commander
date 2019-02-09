<?php

declare(strict_types=1);

namespace MHilker\Example;

use DateTimeImmutable;
use MHilker\EventSourcing\AggregateId;
use MHilker\EventSourcing\Event\Event;

class TestWasCreatedEvent implements Event
{
    private $aggregateId;

    private $occurredOn;

    private $payload;

    private function __construct(AggregateId $aggregateId, DateTimeImmutable $occurredOn, array $payload)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredOn = $occurredOn;
        $this->payload = $payload;
    }

    public static function occur(AggregateId $id): TestWasCreatedEvent
    {
        $now = new DateTimeImmutable();
        return new TestWasCreatedEvent($id, $now, []);
    }

    public static function restore(array $event): Event
    {
        $id = new TestId($event['aggregate_id']);
        $now = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $event['occurred_on']);
        $payload = $event['payload'];
        return new TestWasCreatedEvent($id, $now, $payload);
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function getName(): string
    {
        return __CLASS__;
    }

    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
