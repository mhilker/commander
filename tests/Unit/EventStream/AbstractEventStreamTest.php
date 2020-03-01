<?php

declare(strict_types=1);

namespace Commander\Unit\EventStream;

use Commander\ID\UUID;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\EventStream\AbstractEventStream
 */
final class AbstractEventStreamTest extends TestCase
{
    public function test(): void
    {
        $id = UUID::generateV4();

        $eventStream = StubEventStream::create($id);
        $messages = $eventStream->popMessages();

        $this->assertCount(1, $messages);
        $messages = iterator_to_array($messages);
        $this->assertEquals(new StubEvent($id), $messages[0]->getEvent());
    }
}
