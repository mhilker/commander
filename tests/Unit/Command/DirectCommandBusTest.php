<?php

declare(strict_types=1);

namespace Commander\Unit\Command;

use Commander\Command\Command;
use Commander\Command\CommandHandlers;
use Commander\Command\DirectCommandBus;
use Commander\Command\Exception\CommandFailedException;
use Commander\Command\MemoryCommandPublisher;
use Commander\Event\EventDispatcher;
use Commander\EventStore\EventContext;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\Command\DirectCommandBus
 */
final class DirectCommandBusTest extends TestCase
{
    public function test(): void
    {
        $command = $this->createMock(Command::class);

        $commandHandlers = new CommandHandlers([
            get_class($command) => function (Command $input) use ($command) {
                $this->assertEquals($command, $input);
            },
        ]);
        $context = new EventContext();
        $publisher = new MemoryCommandPublisher();
        $dispatcher = $this->createMock(EventDispatcher::class);
        $dispatcher->expects($this->once())->method('dispatch');

        $commandBus = new DirectCommandBus($commandHandlers, $context, $publisher, $dispatcher);
        $commandBus->execute($command);
    }

    public function testThrowsExceptionWhenCommandHandlerFails(): void
    {
        $this->expectException(CommandFailedException::class);
        $this->expectExceptionMessage('Command failed');

        $command = $this->createMock(Command::class);

        $commandHandlers = new CommandHandlers([
            get_class($command) => static function () {
                throw new Exception();
            },
        ]);
        $context = new EventContext();
        $publisher = new MemoryCommandPublisher();
        $dispatcher = $this->createMock(EventDispatcher::class);

        $commandBus = new DirectCommandBus($commandHandlers, $context, $publisher, $dispatcher);
        $commandBus->execute($command);
    }
}
