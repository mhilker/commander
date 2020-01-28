<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Aggregate\AggregateId;
use Commander\EventStore\Exception\EventStoreException;
use PDO;

final class PDOEventStore implements EventStore
{
    private PDO $pdo;

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
                    INSERT INTO 
                        events (aggregate_id, occurred_on, event_type, payload) 
                    VALUES 
                        (:aggregate_id, :occurred_on, :event_type, :payload);
                QUERY;

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'aggregate_id' => $event->getAggregateId()->asString(),
                    'occurred_on'  => $event->getOccurredOn()->format('Y-m-d H:i:s'),
                    'event_type'   => $event->getType(),
                    'payload'      => json_encode($event->getPayload(), JSON_THROW_ON_ERROR, 512)
                ]);
            }

            $this->pdo->commit();
        } catch (\Exception $exception) {
            throw new EventStoreException('Could not store events.', 0, $exception);
        }
    }

    public function load(AggregateId $id): StorableEvents
    {
        $query = <<<QUERY
            SELECT 
                * 
            FROM 
                events 
            WHERE 
                aggregate_id = :aggregate_id;
        QUERY;

        $statement = $this->pdo->prepare($query);
        $statement->execute([
            'aggregate_id' => $id->asString(),
        ]);

        if ($statement->rowCount() === 0) {
            throw new EventStoreException('No events for aggregate found.');
        }

        $events = StorableEvents::from();

        while ($row = $statement->fetch()) {
            $type = $row['event_type'];
            // TODO: $class anhand von $type bestimmen
            $event = $class::restore($row);
            $events->add($event);
        }

        return $events;
    }
}
