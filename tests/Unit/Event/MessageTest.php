<?php

declare(strict_types=1);

namespace Commander\Unit;

use Commander\Event\Event;
use Commander\Event\Message;
use Commander\ID\Identifier;
use Commander\ID\UUID;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\Event\Message
 */
final class MessageTest extends TestCase
{
    public function testWrapsEvent(): void
    {
        $eventStreamId = UUID::fromV4('eec6caac-bd0a-4da5-9cb0-225b4a1c6aac');
        $event = $this->createMock(Event::class);

        $now = new DateTimeImmutable();
        $message = Message::wrap($eventStreamId, 1, $event);

        $this->assertRegExp(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $message->getId()->asString()
        );
        $this->assertEquals($eventStreamId, $message->getEventStreamId());
        $this->assertEquals(1, $message->getEventStreamVersion());
        $this->assertEqualsWithDelta($now->getTimestamp(), $message->getOccurredOn()->getTimestamp(), 2);
        $this->assertEquals($event, $message->getEvent());
    }

    public function testMessageIdsAreDifferent(): void
    {
        $eventStreamId = UUID::fromV4('eec6caac-bd0a-4da5-9cb0-225b4a1c6aac');
        $event = $this->createMock(Event::class);

        $message1 = Message::wrap($eventStreamId, 1, $event);
        $message2 = Message::wrap($eventStreamId, 1, $event);

        $this->assertNotEquals($message1->getId(), $message2->getId());
    }

    public function testReconstitutesFromArray(): void
    {
        $data = [
            'event_id'             => '3a36885c-427e-4cb5-8f82-8012c5a048b5',
            'occurred_on'          => '2020-03-01 13:28:47',
            'event_stream_id'      => '0c227743-9883-49a0-aa23-bb3758c7b8ff',
            'event_stream_version' => '1',
        ];
        $event = $this->createMock(Event::class);

        $message = Message::reconstitute($data, $event);

        $this->assertEquals('3a36885c-427e-4cb5-8f82-8012c5a048b5', $message->getId()->asString());
        $this->assertEquals('0c227743-9883-49a0-aa23-bb3758c7b8ff', $message->getEventStreamId()->asString());
        $this->assertEquals(1, $message->getEventStreamVersion());
        $this->assertEquals('2020-03-01T13:28:47+00:00', $message->getOccurredOn()->format(DATE_ATOM));
        $this->assertEquals($event, $message->getEvent());
    }
}
