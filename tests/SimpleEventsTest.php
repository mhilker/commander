<?php

declare(strict_types=1);

namespace Commander;

use Commander\Event\Event;
use Commander\Event\SplQueueEventPublisher;
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
        $eventPublisher = new SplQueueEventPublisher();
        $eventBus = $this->createEventBus($eventPublisher, $eventHandler1, $eventHandler2);
        $repository = $this->createRepository($eventStore, $eventPublisher);

        $commands = [
            RegisterUserCommand::class => new RegisterUserCommandHandler($repository),
            RenameUserCommand::class => new RenameUserCommandHandler($repository),
        ];

        $commandBus = $this->createCommandBus($commands);
        $commandBus->execute(new RegisterUserCommand(
            UserId::from('804e777b-b680-4913-90e1-9e0a18d2682c'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('804e777b-b680-4913-90e1-9e0a18d2682c'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RegisterUserCommand(
            UserId::from('f573f6dd-915f-400f-8110-c66193db03ec'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('f573f6dd-915f-400f-8110-c66193db03ec'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('f573f6dd-915f-400f-8110-c66193db03ec'),
            UserName::from('Test Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('f573f6dd-915f-400f-8110-c66193db03ec'),
            UserName::from('Test Tester'),
        ));

        $eventBus->dispatch();
    }
}
