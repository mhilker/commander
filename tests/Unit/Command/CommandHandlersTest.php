<?php

declare(strict_types=1);

namespace Commander\Unit\Command;

use Commander\Command\Command;
use Commander\Command\CommandHandlers;
use Commander\Command\Exception\CommandHandlerNotFoundException;
use Commander\Command\Exception\InvalidCommandClassException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\Command\CommandHandlers
 */
final class CommandHandlersTest extends TestCase
{
    public function testReturnsHandlerForCommand(): void
    {
        $command1 = $this->createMock(Command::class);
        $command2 = $this->createMock(Command::class);
        $command3 = $this->createMock(Command::class);

        $commandHandler1 = static function (Command $command) {};
        $commandHandler2 = static function (Command $command) {};
        $commandHandler3 = static function (Command $command) {};

        $commandHandlers = new CommandHandlers([
            get_class($command1) => $commandHandler1,
            get_class($command2) => $commandHandler2,
            get_class($command3) => $commandHandler3,
        ]);

        $this->assertTrue($commandHandlers->has(get_class($command1)));
        $this->assertTrue($commandHandlers->has(get_class($command2)));
        $this->assertTrue($commandHandlers->has(get_class($command3)));
        $this->assertFalse($commandHandlers->has('not-mapped-class-name'));

        $handler = $commandHandlers->getHandlerForCommand(get_class($command1));
        $this->assertEquals($commandHandler1, $handler);
    }

    public function testThrowsExceptionWhenCommandClassDoesNotExists(): void
    {
        $this->expectException(InvalidCommandClassException::class);
        $this->expectExceptionMessage('Command does not exists');

        new CommandHandlers([
            'not-mapped-class-name' => static function (Command $command) {},
        ]);
    }

    public function testThrowsExceptionWhenCommandWasNotMapped(): void
    {
        $this->expectException(CommandHandlerNotFoundException::class);
        $this->expectExceptionMessage('Could not find handler for command');

        $command1 = $this->createMock(Command::class);

        $commandHandlers = new CommandHandlers([
            get_class($command1) => static function (Command $command) {},
        ]);

        $commandHandlers->getHandlerForCommand('not-mapped-class-name');
    }
}
