<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Aggregate\AggregateId;
use Commander\EventStore\Exception\EventStoreException;
use Commander\UUID;
use Exception;
use PDO;

final class CorrelatingPDOEventStore implements CorrelatingEventStore
{
    private PDO $pdo;

    private EventTopicMap $map;

    private UUID $currentCorrelationId;

    private UUID $currentCausationId;

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
                INSERT INTO `correlating_events` (
                    `event_id`, 
                    `correlation_id`, 
                    `causation_id`, 
                    `aggregate_id`, 
                    `occurred_on`, 
                    `topic`, 
                    `version`, 
                    `payload`
                ) 
                VALUES (
                    :event_id, 
                    :correlation_id, 
                    :causation_id, 
                    :aggregate_id, 
                    :occurred_on, 
                    :topic, 
                    :version, 
                    :payload
                );
            QUERY;

            $statement = $this->pdo->prepare($sql);

            foreach ($events as $event) {
                $statement->execute([
                    'event_id'       => $event->getId()->asString(),
                    'correlation_id' => $this->currentCorrelationId->asString(),
                    'causation_id'   => $this->currentCausationId->asString(),
                    'aggregate_id'   => $event->getAggregateId()->asString(),
                    'occurred_on'    => $event->getOccurredOn()->format('Y-m-d H:i:s'),
                    'topic'          => $event->getTopic(),
                    'version'        => 1,
                    'payload'        => json_encode($event->getPayload(), JSON_THROW_ON_ERROR)
                ]);
            }

            $this->pdo->commit();
        } catch (Exception $exception) {
            $this->pdo->rollBack();
            throw new EventStoreException('Could not store events.', 0, $exception);
        }
    }

    public function load(AggregateId $id): StorableEvents
    {
        $query = <<<QUERY
            SELECT 
                * 
            FROM 
                `correlating_events` 
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

    public function useCorrelationId(UUID $id): void
    {
        $this->currentCorrelationId = $id;
    }

    public function useCausationId(UUID $id): void
    {
        $this->currentCausationId = $id;
    }
}
