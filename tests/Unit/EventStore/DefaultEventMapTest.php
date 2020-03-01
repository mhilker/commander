<?php

declare(strict_types=1);

namespace Commander\Unit\EventStore;

use Commander\Event\Event;
use Commander\EventStore\DefaultEventMap;
use Composer\EventDispatcher\EventSubscriberInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\EventStore\DefaultEventMap
 */
final class DefaultEventMapTest extends TestCase
{
    public function test(): void
    {
        $map = new DefaultEventMap([
            'topic' => [
                1 => StubEvent::class,
            ],
        ]);

        $message = $map->reconstitute([
            'topic'                => 'topic',
            'version'              => '1',
            'event_id'             => '3a36885c-427e-4cb5-8f82-8012c5a048b5',
            'occurred_on'          => '2020-03-01 13:28:47',
            'event_stream_id'      => '0c227743-9883-49a0-aa23-bb3758c7b8ff',
            'event_stream_version' => '1',
            'payload'              => json_encode([], JSON_THROW_ON_ERROR),
        ]);

        $this->assertInstanceOf(StubEvent::class, $message->getEvent());
    }
}
