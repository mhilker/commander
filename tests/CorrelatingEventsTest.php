<?php

declare(strict_types=1);

namespace Commander;

use Commander\Command\CommandHandlers;
use Commander\Command\DirectCommandBus;
use Commander\Command\MemoryCommandPublisher;
use Commander\Event\DirectEventBus;
use Commander\Event\Event;
use Commander\Event\EventHandlers;
use Commander\Event\MemoryEventPublisher;
use Commander\EventStore\DefaultEventMap;
use Commander\EventStore\PDOEventStore;
use Commander\Stub\Aggregate\AggregateUserRepository;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;
use Commander\Stub\Command\RegisterUserCommand;
use Commander\Stub\Command\RegisterUserCommandHandler;
use Commander\Stub\Command\RenameUserCommand;
use Commander\Stub\Command\RenameUserCommandHandler;
use Commander\Stub\Event\DisableUsersWithBlacklistedNamesPolicy;
use Commander\Stub\Event\RegisterUserWhenUserWasDisabledPolicy;
use Commander\Stub\Event\StubEventHandler;
use Commander\Stub\Event\UserDisabledEvent;
use Commander\Stub\Event\UserRegisteredEvent;
use Commander\Stub\Event\UserRenamedEvent;
use Commander\Stub\EventStore\UserEventStoreAggregateRepository;
use Exception;

class CorrelatingEventsTest extends AbstractTestCase
{
    public function setUp(): void
    {
        $this->createPDO()->exec('TRUNCATE TABLE `events`;');
    }

    /**
     * @throws Exception
     */
    public function testRegistersUser(): void
    {
        $stubEventHandlers = [
            function (Event $event) {
                $this->assertInstanceOf(UserRegisteredEvent::class, $event);
            },
            function (Event $event) {
                $this->assertInstanceOf(UserRenamedEvent::class, $event);
            },
            function (Event $event) {
                $this->assertInstanceOf(UserRegisteredEvent::class, $event);
            },
            function (Event $event) {
                $this->assertInstanceOf(UserRenamedEvent::class, $event);
            },
            function (Event $event) {
                $this->assertInstanceOf(UserRenamedEvent::class, $event);
            },
            function (Event $event) {
                $this->assertInstanceOf(UserRenamedEvent::class, $event);
            },
            function (Event $event) {
                $this->assertInstanceOf(UserDisabledEvent::class, $event);
            },
            function (Event $event) {
                $this->assertInstanceOf(UserRegisteredEvent::class, $event);
            },
        ];

        $events = [
            UserRegisteredEvent::TOPIC => [
                UserRegisteredEvent::VERSION =>  UserRegisteredEvent::class,
            ],
            UserRenamedEvent::TOPIC => [
                UserRenamedEvent::VERSION => UserRenamedEvent::class,
            ],
            UserDisabledEvent::TOPIC => [
                UserDisabledEvent::VERSION => UserDisabledEvent::class,
            ],
        ];

        $commandBus = $this->createCommandBus($events, $stubEventHandlers);
        $commandBus->execute(new RegisterUserCommand(
            UserId::fromV4('7bd09ac0-fa17-40cd-8d77-cfb36433b2c9'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::fromV4('7bd09ac0-fa17-40cd-8d77-cfb36433b2c9'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RegisterUserCommand(
            UserId::fromV4('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('John Doe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::fromV4('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Don Joe'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::fromV4('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Test Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::fromV4('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Test Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::fromV4('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('Test'),
        ));
    }

    private function createCommandBus(array $events, array $stubEventHandlers): DirectCommandBus
    {
        $pdo = $this->createPDO();
        $eventStore = new PDOEventStore($pdo, new DefaultEventMap($events));
        $eventPublisher = new MemoryEventPublisher();
        $aggregateRepository = new UserEventStoreAggregateRepository($eventStore, $eventPublisher);
        $userRepository = new AggregateUserRepository($aggregateRepository);
        $commandPublisher = new MemoryCommandPublisher();
        $eventHandlers = EventHandlers::from([
            new StubEventHandler(...$stubEventHandlers),
            new DisableUsersWithBlacklistedNamesPolicy($userRepository),
            new RegisterUserWhenUserWasDisabledPolicy($commandPublisher),
        ]);
        $eventBus = new DirectEventBus(
            $eventHandlers,
            $eventStore,
            $eventPublisher
        );
        $commandHandlers = new CommandHandlers([
            RegisterUserCommand::class => new RegisterUserCommandHandler($userRepository),
            RenameUserCommand::class => new RenameUserCommandHandler($userRepository),
        ]);

        $commandBus = new DirectCommandBus($commandHandlers, $eventStore, $commandPublisher, $eventBus);
        return $commandBus;
    }
}
