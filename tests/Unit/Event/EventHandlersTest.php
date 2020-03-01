<?php

declare(strict_types=1);

namespace Commander\Unit\Event;

use Commander\Event\EventHandler;
use Commander\Event\EventHandlers;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\Event\EventHandlers
 */
final class EventHandlersTest extends TestCase
{
    public function testContainsEventHandlers(): void
    {
        $eventHandler1 = $this->createMock(EventHandler::class);
        $eventHandler2 = $this->createMock(EventHandler::class);
        $eventHandler3 = $this->createMock(EventHandler::class);

        $eventHandlers = EventHandlers::from([
            $eventHandler1,
            $eventHandler2,
            $eventHandler3,
        ]);

        $this->assertEquals([$eventHandler1, $eventHandler2, $eventHandler3], [...$eventHandlers]);
    }
}
