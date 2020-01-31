<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Aggregate\AggregateId;
use Commander\EventStore\Exception\EventStoreException;
use Exception;
use PDO;

final class PDOEventStore implements EventStore
{
    private PDO $pdo;

    private EventTopicMap $map;

    public function __construct(PDO $pdo, EventTopicMap $map)
    {
        $this->pdo = $pdo;
        $this->map = $map;
    }

    public function store(StorableEvents $events): void
    {
        try {
            $this->pdo->beginTransaction();

            $sql = <<<QUERY
                INSERT INTO 
                    `simple_events` (`aggregate_id`, `occurred_on`, `topic`, `payload`) 
                VALUES 
                    (:aggregate_id, :occurred_on, :topic, :payload);
            QUERY;

            $stmt = $this->pdo->prepare($sql);

            foreach ($events as $event) {
                $stmt->execute([
                    'aggregate_id' => $event->getAggregateId()->asString(),
                    'occurred_on'  => $event->getOccurredOn()->format('Y-m-d H:i:s'),
                    'topic'        => $event->getTopic(),
                    'payload'      => json_encode($event->getPayload(), JSON_THROW_ON_ERROR)
                ]);
            }

            $this->pdo->commit();
        } catch (Exception $exception) {
            throw new EventStoreException('Could not store events.', 0, $exception);
        }
    }

    public function load(AggregateId $id): StorableEvents
    {
        $query = <<<QUERY
            SELECT 
                * 
            FROM 
                `simple_events` 
            WHERE 
                `aggregate_id` = :aggregate_id;
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
            $event = $this->map->restore($row);
            $events->add($event);
        }

        return $events;
    }
}
