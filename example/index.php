<?php

require __DIR__ . '/../vendor/autoload.php';

$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=event_store';
$username = 'root';
$password = 'password';
$options = [
    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
];

$pdo = new \PDO($dsn, $username, $password, $options);

$eventHandlers = new \MHilker\CQRS\Event\EventHandlers();
$eventBus = new \MHilker\CQRS\Event\DirectEventBus($eventHandlers);

$aggregateRepository = new \MHilker\EventSourcing\AggregateRepository($pdo, $eventBus, \MHilker\Example\TestAggregate::class);

$repository = new \MHilker\Example\TestRepository($aggregateRepository);

$commandHandler = new \MHilker\Example\TestCommandHandler($repository);

$command = new \MHilker\Example\TestCommand();

$commandHandlers = new \MHilker\CQRS\Command\CommandHandlers();
$commandHandlers->addHandler($commandHandler, \MHilker\Example\TestCommand::class);
$commandBus = new \MHilker\CQRS\Command\DirectCommandBus($commandHandlers);
$commandBus->execute($command);
