<?php

declare(strict_types=1);

namespace Commander;

use Commander\Event\Event;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;
use Commander\Stub\Command\RegisterUserCommand;
use Commander\Stub\Command\RegisterUserCommandHandler;
use Commander\Stub\Command\RenameUserCommand;
use Commander\Stub\Command\RenameUserCommandHandler;
use Commander\Stub\Event\UserRegisteredEvent;
use Commander\Stub\Event\UserRenamedEvent;
use Exception;

class SimpleEventsTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->createPDO()->exec('TRUNCATE TABLE `simple_events`;');
    }

    /**
     * @throws Exception
     */
    public function testRegistersUser(): void
    {
        $eventHandler1 = function (Event $event) {
            $this->assertInstanceOf(UserRegisteredEvent::class, $event);
        };
        $eventHandler2 = function (Event $event) {
            $this->assertInstanceOf(UserRenamedEvent::class, $event);
        };

        $events = [
            UserRegisteredEvent::TOPIC => UserRegisteredEvent::class,
            UserRenamedEvent::TOPIC => UserRenamedEvent::class,
        ];

        $pdo = $this->createPDO();
        $eventStore = $this->createEventStore($pdo, $events);
        $eventBus = $this->createEventBus($eventHandler1, $eventHandler2);
        $repository = $this->createRepository($eventStore, $eventBus);

        $commands = [
            RegisterUserCommand::class => new RegisterUserCommandHandler($repository),
            RenameUserCommand::class => new RenameUserCommandHandler($repository),
        ];

        $commandBus = $this->createCommandBus($commands);
        $commandBus->execute(new RegisterUserCommand(
            UserId::from('bcc2ab4c-4403-11ea-87c1-73599d952a81'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('bcc2ab4c-4403-11ea-87c1-73599d952a81'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RegisterUserCommand(
            UserId::from('6b980f2c-442c-11ea-9ed3-abbde45d135b'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('6b980f2c-442c-11ea-9ed3-abbde45d135b'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('6b980f2c-442c-11ea-9ed3-abbde45d135b'),
            UserName::from('Test Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('6b980f2c-442c-11ea-9ed3-abbde45d135b'),
            UserName::from('Test Tester'),
        ));

        $eventBus->dispatch();
    }
}
