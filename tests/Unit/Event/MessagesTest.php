<?php

declare(strict_types=1);

namespace Commander\Unit\Event;

use Commander\Event\Event;
use Commander\Event\Message;
use Commander\Event\Messages;
use Commander\ID\UUID;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\Event\Messages
 */
final class MessagesTest extends TestCase
{
    public function testCreatesMessages(): void
    {
        $event1 = $this->createMock(Event::class);
        $event2 = $this->createMock(Event::class);
        $event3 = $this->createMock(Event::class);

        $messages = Messages::from([
            Message::wrap(UUID::fromV4('eec6caac-bd0a-4da5-9cb0-225b4a1c6aac'), 1, $event1),
            Message::wrap(UUID::fromV4('6a81d84b-7666-45df-a1d0-0ffd7b4ee955'), 2, $event2),
            Message::wrap(UUID::fromV4('280ba8de-57b5-4178-8bb4-4f5580919e57'), 3, $event3),
        ]);

        $this->assertCount(3, $messages);
        $this->assertContainsOnlyInstancesOf(Message::class, $messages);
    }
}
