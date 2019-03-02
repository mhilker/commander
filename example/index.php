<?php

declare(strict_types=1);

use MHilker\CQRS\Aggregate\EventStoreAggregateRepository;
use MHilker\CQRS\Command\CommandHandlers;
use MHilker\CQRS\Command\DirectCommandBus;
use MHilker\CQRS\Event\DirectEventBus;
use MHilker\CQRS\Event\EventHandlers;
use MHilker\CQRS\EventStore\PDOEventStore;
use MHilker\Example\TestAggregate;
use MHilker\Example\TestCommand;
use MHilker\Example\TestCommandHandler;
use MHilker\Example\TestId;
use MHilker\Example\TestRepository;

require __DIR__ . '/../vendor/autoload.php';

$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=cqrs_example';
$username = 'root';
$password = '1234';
$options = [
    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
];

$pdo = new \PDO($dsn, $username, $password, $options);
$eventStore = new PDOEventStore($pdo);

$eventBus = new DirectEventBus(EventHandlers::from());

$aggregateRepository = new EventStoreAggregateRepository($eventStore, $eventBus, TestAggregate::class);

$repository = new TestRepository($aggregateRepository);

$commandHandlers = new CommandHandlers([
    TestCommand::class => new TestCommandHandler($repository),
]);

$command = new TestCommand(TestId::generate(), 'Test');
$commandBus = new DirectCommandBus($commandHandlers);
$commandBus->execute($command);
