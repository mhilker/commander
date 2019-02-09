<?php

declare(strict_types=1);

namespace MHilker\EventSourcing\Repository;

use MHilker\CQRS\Event\DirectEventBus;
use MHilker\EventSourcing\AbstractAggregateRoot;
use MHilker\EventSourcing\AggregateId;
use MHilker\EventSourcing\Repository\AggregateRepositoryInterface;
use MHilker\EventSourcing\Event\Events;
use MHilker\EventSourcing\Exception\AggregateNotFoundException;

class AggregateRepository implements AggregateRepositoryInterface
{
    private $pdo;

    private $eventBus;

    private $aggregateClass;

    public function __construct(\PDO $pdo, DirectEventBus $eventBus, string $aggregateClass)
    {
        $this->pdo = $pdo;
        $this->eventBus = $eventBus;
        $this->aggregateClass = $aggregateClass;
    }

    public function save(AbstractAggregateRoot $aggregate): void
    {
        $events = $aggregate->popEvents();

        $this->pdo->beginTransaction();

        foreach ($events as $event) {
            $sql = "INSERT INTO events (aggregate_id, occurred_on, event_type, payload) VALUES(:aggregate_id, :occurred_on, :event_type, :payload);";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'aggregate_id' => $aggregate->getAggregateId()->asString(),
                'occurred_on'  => $event->getOccurredOn()->format('Y-m-d H:i:s'),
                'event_type'   => get_class($event),
                'payload'      => json_encode($event->getPayload())
            ]);
        }

        $this->pdo->commit();

        foreach ($events as $event) {
            $this->eventBus->trigger($event);
        }
    }

    public function load(AggregateId $id): AbstractAggregateRoot
    {
        $query = 'SELECT * FROM events WHERE aggregate_id = :aggregate_id;';
        $statement = $this->pdo->prepare($query);
        $statement->execute([
            'aggregate_id' => $id->asString(),
        ]);

        if ($statement->rowCount() === 0) {
            throw new AggregateNotFoundException();
        }

        $events = new Events();

        while ($event = $statement->fetch()) {
            $class = $event['event_type'];
            $event = $class::restore($event);
            $events->addEvent($event);
        }

        return ($this->aggregateClass)::reconstituteFromHistory($events);
    }
}
