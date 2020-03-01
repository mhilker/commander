<?php

declare(strict_types=1);

namespace Commander\Unit\Command;

use Commander\Command\Command;
use Commander\Command\MemoryCommandPublisher;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\Command\MemoryCommandPublisher
 */
final class MemoryCommandPublisherTest extends TestCase
{
    public function testEnqueuesCommands(): void
    {
        $command1 = $this->createMock(Command::class);
        $command2 = $this->createMock(Command::class);
        $command3 = $this->createMock(Command::class);

        $publisher = new MemoryCommandPublisher();
        $publisher->publish($command1);
        $publisher->publish($command2);
        $publisher->publish($command3);

        $this->assertFalse($publisher->isEmpty());
        $this->assertEquals($command1, $publisher->dequeue());
        $this->assertFalse($publisher->isEmpty());
        $this->assertEquals($command2, $publisher->dequeue());
        $this->assertFalse($publisher->isEmpty());
        $this->assertEquals($command3, $publisher->dequeue());
        $this->assertTrue($publisher->isEmpty());
    }
}
