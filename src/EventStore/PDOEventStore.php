<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Messages;
use Commander\EventStore\Exception\EventStoreException;
use Commander\Util\Identifier;
use Exception;
use PDO;

final class PDOEventStore implements CorrelatingEventStore
{
    private PDO $pdo;
    private EventTopicMap $map;
    private Identifier $currentCorrelationId;
    private Identifier $currentCausationId;

    public function __construct(PDO $pdo, EventTopicMap $map)
    {
        $this->pdo = $pdo;
        $this->map = $map;
    }

    public function store(Messages $messages): void
    {
        try {
            $this->pdo->beginTransaction();

            $sql = <<<QUERY
                INSERT INTO `events` (
                    `event_id`, 
                    `correlation_id`, 
                    `causation_id`, 
                    `aggregate_id`, 
                    `aggregate_version`, 
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
                    :aggregate_version, 
                    :occurred_on, 
                    :topic, 
                    :version, 
                    :payload
                );
            QUERY;

            $statement = $this->pdo->prepare($sql);

            foreach ($messages as $message) {
                $statement->execute([
                    'event_id'          => $message->getId()->asString(),
                    'correlation_id'    => $this->currentCorrelationId->asString(),
                    'causation_id'      => $this->currentCausationId->asString(),
                    'aggregate_id'      => $message->getAggregateId()->asString(),
                    'aggregate_version' => $message->getAggregateVersion(),
                    'occurred_on'       => $message->getOccurredOn()->format('Y-m-d H:i:s'),
                    'topic'             => $message->getEvent()->getTopic(),
                    'version'           => $message->getEvent()->getVersion(),
                    'payload'           => json_encode($message->getEvent()->getPayload(), JSON_THROW_ON_ERROR)
                ]);
            }

            $this->pdo->commit();
        } catch (Exception $exception) {
            $this->pdo->rollBack();
            throw new EventStoreException('Could not store events.', 0, $exception);
        }
    }

    public function load(Identifier $id): Messages
    {
        $query = <<<QUERY
            SELECT 
                * 
            FROM 
                `events` 
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

        $messages = [];
        while ($row = $statement->fetch()) {
            $messages[] = $this->map->reconstitute($row);
        }

        return Messages::from($messages);
    }

    public function useCorrelationId(Identifier $id): void
    {
        $this->currentCorrelationId = $id;
    }

    public function useCausationId(Identifier $id): void
    {
        $this->currentCausationId = $id;
    }
}
