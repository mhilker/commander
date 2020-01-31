<?php

declare(strict_types=1);

namespace Commander;

use Commander\Event\Events;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Command\RegisterUserCommand;
use Commander\Stub\Command\RegisterUserCommandHandler;
use Commander\Stub\Command\RenameUserCommand;
use Commander\Stub\Command\RenameUserCommandHandler;
use Commander\Stub\Event\UserRegisteredEvent;
use Commander\Stub\Event\UserRenamedEvent;

class EndToEndTest extends AbstractTestCase
{
    public function testRegistersUser(): void
    {
        $callable = function (Events $events) {
            $this->assertCount(1, $events);
            $this->assertContainsOnlyInstancesOf(UserRegisteredEvent::class, $events);
        };

        $events = [
            UserRegisteredEvent::TOPIC => UserRegisteredEvent::class,
            UserRenamedEvent::TOPIC => UserRenamedEvent::class,
        ];

        $pdo        = $this->createPDO();
        $eventStore = $this->createEventStore($pdo, $events);
        $eventBus   = $this->createEventBus($callable);
        $repository = $this->createRepository($eventStore, $eventBus);

        $commands = [
            RegisterUserCommand::class => new RegisterUserCommandHandler($repository),
            RenameUserCommand::class => new RenameUserCommandHandler($repository),
        ];

        $command = new RegisterUserCommand(UserId::generate(), 'Test');

        $commandBus = $this->createCommandBus($commands);
        $commandBus->execute($command);
    }
}
