<?php

declare(strict_types=1);

namespace Commander;

use Commander\Command\CommandHandlers;
use Commander\Command\DirectCommandBus;
use Commander\Event\DirectEventBus;
use Commander\Event\EventHandlers;
use Commander\Event\Events;
use Commander\EventStore\EventTopicMap;
use Commander\EventStore\PDOEventStore;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserRepository;
use Commander\Stub\Command\RenameUserCommand;
use Commander\Stub\Command\RenameUserCommandHandler;
use Commander\Stub\Command\RegisterUserCommand;
use Commander\Stub\Command\RegisterUserCommandHandler;
use Commander\Stub\Event\UserRenamedEvent;
use Commander\Stub\Event\StubEventHandler;
use Commander\Stub\Event\UserRegisteredEvent;
use Commander\Stub\EventStore\UserEventStoreAggregateRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class EndToEndTest extends TestCase
{
    public function testRegistersUser(): void
    {
        $callable = function (Events $events) {
            $this->assertCount(1, $events);
            $this->assertContainsOnlyInstancesOf(UserRegisteredEvent::class, $events);
        };

        $map = [
            UserRegisteredEvent::TOPIC => UserRegisteredEvent::class,
            UserRenamedEvent::TOPIC => UserRenamedEvent::class,
        ];

        $pdo        = $this->createPDO();
        $eventStore = $this->createEventStore($pdo, $map);
        $eventBus   = $this->createEventBus($callable);
        $repository = $this->createRepository($eventStore, $eventBus);
        $commandBus = $this->createCommandBus($repository);

        $command = new RegisterUserCommand(UserId::generate(), 'Test');
        $commandBus->execute($command);
    }

    private function createCommandBus(UserRepository $repository): DirectCommandBus
    {
        $commandHandlers = new CommandHandlers([
            RegisterUserCommand::class => new RegisterUserCommandHandler($repository),
            RenameUserCommand::class => new RenameUserCommandHandler($repository),
        ]);

        return new DirectCommandBus($commandHandlers);
    }

    private function createEventBus(callable $callable): DirectEventBus
    {
        $handler = new StubEventHandler($callable);

        $handlers = EventHandlers::from([
            $handler,
        ]);

        return new DirectEventBus($handlers);
    }

    private function createRepository(PDOEventStore $eventStore, DirectEventBus $eventBus): UserRepository
    {
        $aggregateRepository = new UserEventStoreAggregateRepository($eventStore, $eventBus);
        return new UserRepository($aggregateRepository);
    }

    private function createEventStore(PDO $pdo, array $map): PDOEventStore
    {
        $map = new EventTopicMap($map);
        return new PDOEventStore($pdo, $map);
    }

    private function createPDO(): PDO
    {
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=event_store';
        $username = 'root';
        $password = 'password';
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        return new PDO($dsn, $username, $password, $options);
    }
}
