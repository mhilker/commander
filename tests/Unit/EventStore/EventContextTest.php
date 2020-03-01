<?php

declare(strict_types=1);

namespace Commander\Unit\EventStore;

use Commander\EventStore\EventContext;
use Commander\ID\UUID;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\EventStore\EventContext
 */
final class EventContextTest extends TestCase
{
    public function testHoldsCorrelationId(): void
    {
        $correlationId = UUID::generateV4();

        $object = new EventContext();
        $object->setCurrentCorrelationId($correlationId);

        $this->assertEquals($correlationId, $object->getCurrentCorrelationId());
    }

    public function testHoldsCausationId(): void
    {
        $causationId = UUID::generateV4();

        $object = new EventContext();
        $object->setCurrentCausationId($causationId);

        $this->assertEquals($causationId, $object->getCurrentCausationId());
    }
}
