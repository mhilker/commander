<?php

declare(strict_types=1);

namespace Commander;

use Commander\Command\CommandHandlers;
use Commander\Command\DirectCommandBus;
use Commander\Event\DirectEventBus;
use Commander\Event\EventHandlers;
use Commander\EventStore\EventTopicMap;
use Commander\EventStore\PDOEventStore;
use Commander\Stub\Aggregate\UserRepository;
use Commander\Stub\Event\StubEventHandler;
use Commander\Stub\EventStore\UserEventStoreAggregateRepository;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function createCommandBus(array $map): DirectCommandBus
    {
        $commandHandlers = new CommandHandlers($map);
        return new DirectCommandBus($commandHandlers);
    }

    protected function createEventBus(callable $callable): DirectEventBus
    {
        $handler = new StubEventHandler($callable);
        $handlers = EventHandlers::from([
            $handler,
        ]);
        return new DirectEventBus($handlers);
    }

    protected function createRepository(PDOEventStore $eventStore, DirectEventBus $eventBus): UserRepository
    {
        $aggregateRepository = new UserEventStoreAggregateRepository($eventStore, $eventBus);
        return new UserRepository($aggregateRepository);
    }

    protected function createEventStore(PDO $pdo, array $map): PDOEventStore
    {
        $map = new EventTopicMap($map);
        return new PDOEventStore($pdo, $map);
    }

    protected function createPDO(): PDO
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
