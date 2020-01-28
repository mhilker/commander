<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

use Commander\Aggregate\AbstractAggregate;
use Commander\Aggregate\AggregateId;
use Commander\Event\Event;
use Commander\Stub\Event\NameWasChangedEvent;
use Commander\Stub\Event\TestWasCreatedEvent;

class TestAggregate extends AbstractAggregate
{
    private AggregateId $aggregateId;

    private string $name;

    public static function create(AggregateId $id, string $name): TestAggregate
    {
        $test = new self(null);
        $test->record(TestWasCreatedEvent::occur($id, $name));
        return $test;
    }

    public function changeName(string $newName): void
    {
        if ($newName !== $this->name) {
            $this->record(NameWasChangedEvent::occur($this->aggregateId, $newName));
        }
    }

    protected function apply(Event $event): void
    {
        switch ($event->getType()) {
            case TestWasCreatedEvent::TOPIC:
                /** @var TestWasCreatedEvent $event */
                $this->applyTestCreated($event);
            break;
            case NameWasChangedEvent::TOPIC:
                /** @var NameWasChangedEvent $event */
                $this->applyNameChanged($event);
            break;
        }
    }

    protected function applyTestCreated(TestWasCreatedEvent $event): void
    {
        $this->aggregateId = $event->getAggregateId();
        $this->name = $event->getName();
    }

    protected function applyNameChanged(NameWasChangedEvent $event): void
    {
        $this->name = $event->getName();
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }
}
