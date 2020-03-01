<?php

declare(strict_types=1);

namespace Commander\Unit\Event;

use Commander\Event\DirectEventBus;
use Commander\Event\Event;
use Commander\Event\EventHandler;
use Commander\Event\EventHandlers;
use Commander\Event\MemoryEventPublisher;
use Commander\Event\Message;
use Commander\Event\Messages;
use Commander\EventStore\EventContext;
use Commander\ID\UUID;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\Event\DirectEventBus
 */
final class DirectEventBusTest extends TestCase
{
    public function test(): void
    {
        $event1 = $this->createMock(Event::class);
        $event2 = $this->createMock(Event::class);
        $event3 = $this->createMock(Event::class);

        $messages = Messages::from([
            Message::wrap(UUID::fromV4('4f2633ee-0f67-4d67-a663-98ad9651a646'), 1, $event1),
            Message::wrap(UUID::fromV4('2ebb2db8-825a-499c-b8a4-4e314576850d'), 2, $event2),
            Message::wrap(UUID::fromV4('86b6948b-becf-4a8c-bac8-fcea403105ae'), 3, $event3),
        ]);

        $eventHandler1 = $this->createMock(EventHandler::class);
        $eventHandler1->expects($this->exactly(3))->method('handle')->withConsecutive([$event1], [$event2], [$event3]);
        $eventHandler2 = $this->createMock(EventHandler::class);
        $eventHandler2->expects($this->exactly(3))->method('handle')->withConsecutive([$event1], [$event2], [$event3]);
        $eventHandler3 = $this->createMock(EventHandler::class);
        $eventHandler3->expects($this->exactly(3))->method('handle')->withConsecutive([$event1], [$event2], [$event3]);

        $handlers = EventHandlers::from([
            $eventHandler1,
            $eventHandler2,
            $eventHandler3,
        ]);

        $context = new EventContext();
        $publisher = new MemoryEventPublisher();
        $publisher->publish($messages);

        $dispatcher = new DirectEventBus($handlers, $context, $publisher);
        $dispatcher->dispatch();
    }
}
