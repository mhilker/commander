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
use Commander\Stub\Command\CreateTestCommand;
use Commander\Stub\Command\CreateTestCommandHandler;
use Commander\Stub\Event\TestEventHandler;
use Commander\Stub\Aggregate\TestId;
use Commander\Stub\Aggregate\TestRepository;
use Commander\Stub\Event\TestWasCreatedEvent;
use PHPUnit\Framework\TestCase;

class EndToEndTest extends TestCase
{
    public function test(): void
    {
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=event_store';
        $username = 'root';
        $password = 'password';
        $options = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        ];

        $pdo = new \PDO($dsn, $username, $password, $options);
        $eventStore = new PDOEventStore($pdo);

        $eventBus = new DirectEventBus(EventHandlers::from([
            new TestEventHandler(function (Events $events) {
                $this->assertCount(1, $events);
                $this->assertContainsOnlyInstancesOf(TestWasCreatedEvent::class, $events);
            })
        ]));

        $aggregateRepository = new EventStoreAggregateRepository($eventStore, $eventBus, TestAggregate::class);
        $repository = new TestRepository($aggregateRepository);

        $commandHandlers = new CommandHandlers([
            CreateTestCommand::class => new CreateTestCommandHandler($repository),
        ]);

        $command = new CreateTestCommand(TestId::generate(), 'Test');
        $commandBus = new DirectCommandBus($commandHandlers);
        $commandBus->execute($command);
    }
}
