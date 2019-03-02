<?php

declare(strict_types=1);

namespace MHilker\Example;

use MHilker\CQRS\Aggregate\AggregateId;
use MHilker\CQRS\Aggregate\AbstractAggregate;
use MHilker\CQRS\EventStore\StorableEvent;
use MHilker\CQRS\Event\Events;
use MHilker\CQRS\Aggregate\Exception\AggregateEventHandlerMethodNotFound;

class TestAggregate extends AbstractAggregate
{
    private $aggregateId;

    public static function create(AggregateId $id, string $name): TestAggregate
    {
        $test = new self(null);
        $test->record(TestWasCreatedEvent::occur($id, $name));
        return $test;
    }

    protected function apply(StorableEvent $event): void
    {
        switch ($event->getType()) {
            case 'com.example.test':
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
