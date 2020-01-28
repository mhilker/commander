<?php

declare(strict_types=1);

namespace Commander;

use Commander\Aggregate\EventStoreAggregateRepository;
use Commander\Command\CommandHandlers;
use Commander\Command\DirectCommandBus;
use Commander\Event\DirectEventBus;
use Commander\Event\EventHandlers;
use Commander\Event\Events;
use Commander\EventStore\PDOEventStore;
use Commander\Stub\Aggregate\TestAggregate;
use Commander\Stub\Aggregate\TestId;
use Commander\Stub\Aggregate\TestRepository;
use Commander\Stub\Command\ChangeNameCommand;
use Commander\Stub\Command\ChangeNameCommandHandler;
use Commander\Stub\Command\CreateTestCommand;
use Commander\Stub\Command\CreateTestCommandHandler;
use Commander\Stub\Event\TestEventHandler;
use Commander\Stub\Event\TestWasCreatedEvent;
use PDO;
use PHPUnit\Framework\TestCase;

class EndToEndTest extends TestCase
{
    public function test(): void
    {
        $callable = function (Events $events) {
            $this->assertCount(1, $events);
            $this->assertContainsOnlyInstancesOf(TestWasCreatedEvent::class, $events);
        };

        $pdo        = $this->createPDO();
        $eventStore = $this->createEventStore($pdo);
        $eventBus   = $this->createEventBus($callable);
        $repository = $this->createRepository($eventStore, $eventBus);
        $commandBus = $this->createCommandBus($repository);

        $command = new CreateTestCommand(TestId::generate(), 'Test');
        $commandBus->execute($command);
    }

    private function createCommandBus(TestRepository $repository): DirectCommandBus
    {
        $commandHandlers = new CommandHandlers([
            CreateTestCommand::class => new CreateTestCommandHandler($repository),
            ChangeNameCommand::class => new ChangeNameCommandHandler($repository),
        ]);

        return new DirectCommandBus($commandHandlers);
    }

    private function createEventBus(callable $callable): DirectEventBus
    {
        $handler = new TestEventHandler($callable);

        $handlers = EventHandlers::from([
            $handler,
        ]);

        return new DirectEventBus($handlers);
    }

    private function createRepository(PDOEventStore $eventStore, DirectEventBus $eventBus): TestRepository
    {
        $aggregateRepository = new EventStoreAggregateRepository($eventStore, $eventBus, TestAggregate::class);
        return new TestRepository($aggregateRepository);
    }

    private function createEventStore(PDO $pdo): PDOEventStore
    {
        return new PDOEventStore($pdo);
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
