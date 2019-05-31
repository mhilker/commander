<?php

declare(strict_types=1);

namespace Commander\Stub;

use Commander\Aggregate\AbstractAggregate;
use Commander\Aggregate\AggregateId;
use Commander\Event\Event;

class TestAggregate extends AbstractAggregate
{
    private $aggregateId;

    public static function create(AggregateId $id, string $name): TestAggregate
    {
        $test = new self(null);
        $test->record(TestWasCreatedEvent::occur($id, $name));
        return $test;
    }

    protected function apply(Event $event): void
    {
        switch ($event->getType()) {
            case 'com.example.event.email_was_added':
                /** @var TestWasCreatedEvent $event */
                $this->applyTestCreated($event);
            break;
        }
    }

    protected function applyTestCreated(TestWasCreatedEvent $event): void
    {
        $this->aggregateId = $event->getAggregateId();
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }
}
