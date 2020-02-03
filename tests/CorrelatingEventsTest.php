<?php

declare(strict_types=1);

namespace Commander;

use Commander\Command\CorrelatingCommandBus;
use Commander\Event\CorrelatingDirectEventBus;
use Commander\Event\Event;
use Commander\Event\EventHandlers;
use Commander\Event\SplQueueEventPublisher;
use Commander\EventStore\CorrelatingPDOEventStore;
use Commander\EventStore\EventTopicMap;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;
use Commander\Stub\Command\RegisterUserCommand;
use Commander\Stub\Command\RegisterUserCommandHandler;
use Commander\Stub\Command\RenameUserCommand;
use Commander\Stub\Command\RenameUserCommandHandler;
use Commander\Stub\Event\DisableUsersWithBlacklistedNamesPolicy;
use Commander\Stub\Event\StubEventHandler;
use Commander\Stub\Event\UserRegisteredEvent;
use Commander\Stub\Event\UserRenamedEvent;
use Exception;

class CorrelatingEventsTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->createPDO()->exec('TRUNCATE TABLE `correlating_events`;');
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
        $eventStore = new CorrelatingPDOEventStore($pdo, new EventTopicMap($events));

        $eventPublisher = new SplQueueEventPublisher();
        $repository = $this->createRepository($eventStore, $eventPublisher);

        $eventBus = new CorrelatingDirectEventBus(
            EventHandlers::from([
                new StubEventHandler($eventHandler1, $eventHandler2),
                new DisableUsersWithBlacklistedNamesPolicy($repository),
            ]),
            $eventStore,
            $eventPublisher
        );
        $repository = $this->createRepository($eventStore, $eventPublisher);

        $commands = [
            RegisterUserCommand::class => new RegisterUserCommandHandler($repository),
            RenameUserCommand::class => new RenameUserCommandHandler($repository),
        ];

        $commandBus = new CorrelatingCommandBus($this->createCommandBus($commands), $eventStore);
        $commandBus->execute(new RegisterUserCommand(
            UserId::from('7bd09ac0-fa17-40cd-8d77-cfb36433b2c9'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('7bd09ac0-fa17-40cd-8d77-cfb36433b2c9'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RegisterUserCommand(
            UserId::from('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Test Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Test Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::from('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Test'),
        ));

        $eventBus->dispatch();
    }
}
