<?php

declare(strict_types=1);

namespace Commander\Integration;

use Commander\Command\CommandHandlers;
use Commander\Command\DirectCommandBus;
use Commander\Command\MemoryCommandPublisher;
use Commander\Event\DirectEventBus;
use Commander\Event\Event;
use Commander\Event\EventHandlers;
use Commander\Event\MemoryEventPublisher;
use Commander\EventStore\DefaultEventMap;
use Commander\EventStore\EventContext;
use Commander\EventStore\PDOEventStore;
use Commander\Stub\EventStream\EventStreamUserRepository;
use Commander\Stub\EventStream\UserId;
use Commander\Stub\EventStream\UserName;
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
use Commander\Stub\EventStream\UserEventStreamRepository;
use Exception;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class CorrelatingEventsTest extends TestCase
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
            UserName::from('HasIdentifierTest Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::fromV4('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('HasIdentifierTest Tester'),
        ));
        $commandBus->execute(new RenameUserCommand(
            UserId::fromV4('f5295e41-07ac-43c4-b99a-43247275ae73'),
            UserName::from('HasIdentifierTest'),
        ));
    }

    /**
     * @throws Exception
     */
    private function createCommandBus(array $events, array $stubEventHandlers): DirectCommandBus
    {
        $pdo = $this->createPDO();
        $context = new EventContext();
        $map = new DefaultEventMap($events);
        $eventStore = new PDOEventStore($pdo, $map, $context);
        $eventPublisher = new MemoryEventPublisher();
        $repository = new UserEventStreamRepository($eventStore, $eventPublisher);
        $userRepository = new EventStreamUserRepository($repository);
        $commandPublisher = new MemoryCommandPublisher();
        $eventHandlers = EventHandlers::from([
            new StubEventHandler(...$stubEventHandlers),
            new DisableUsersWithBlacklistedNamesPolicy($userRepository),
            new RegisterUserWhenUserWasDisabledPolicy($commandPublisher),
        ]);
        $eventBus = new DirectEventBus($eventHandlers, $context, $eventPublisher);
        $commandHandlers = new CommandHandlers([
            RegisterUserCommand::class => new RegisterUserCommandHandler($userRepository),
            RenameUserCommand::class => new RenameUserCommandHandler($userRepository),
        ]);
        return new DirectCommandBus($commandHandlers, $context, $commandPublisher, $eventBus);
    }

    protected function createPDO(): PDO
    {
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=event_store';
        $username = 'root';
        $password = 'password';
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        ];
        return new PDO($dsn, $username, $password, $options);
    }
}
