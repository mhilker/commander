<?php

declare(strict_types=1);

namespace MHilker\Example;

use MHilker\EventSourcing\AggregateId;
use MHilker\EventSourcing\AbstractAggregateRoot;

class TestAggregate extends AbstractAggregateRoot
{
    private $aggregateId;

    public static function create(AggregateId $id): TestAggregate
    {
        $test = new static();
        $test->recordThat(TestWasCreatedEvent::occur($id));
        return $test;
    }

    protected function whenTestWasCreated(TestWasCreatedEvent $event)
    {
        $this->aggregateId = $event->getAggregateId();
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }
}
