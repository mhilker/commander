<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Aggregate\AggregateId;
use PDO;

final class PDOEventStore implements EventStore
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function store(StorableEvents $events): void
    {
        try {
            $this->pdo->beginTransaction();

            foreach ($events as $event) {
                $sql = <<<QUERY
                    INSERT INTO events (aggregate_id, occurred_on, event_type, payload) 
                    VALUES (:aggregate_id, :occurred_on, :event_type, :payload);
                QUERY;

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'aggregate_id' => $event->getAggregateId()->asString(),
                    'occurred_on'  => $event->getOccurredOn()->format('Y-m-d H:i:s'),
                    'event_type'   => $event->getType(),
                    'payload'      => json_encode($event->getPayload())
                ]);
            }

            $this->pdo->commit();
        } catch (\Exception $exception) {
            throw new \Exception('', 0, $exception);
        }
    }

    public function load(AggregateId $id): StorableEvents
    {
        $query = <<<QUERY
            SELECT * 
            FROM events 
            WHERE aggregate_id = :aggregate_id;
        QUERY;

        $statement = $this->pdo->prepare($query);
        $statement->execute([
            'aggregate_id' => $id->asString(),
        ]);

        if ($statement->rowCount() === 0) {
            throw new \Exception();
        }

        $events = StorableEvents::from();

        while ($row = $statement->fetch()) {
            // TODO: klassen Mapping
            $class = $row['event_type'];
            $event = $class::restore($row);
            $events->add($event);
        }

        return $events;
    }
}
