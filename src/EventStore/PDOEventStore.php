<?php

declare(strict_types=1);

namespace Commander\EventStore;

use Commander\Event\Messages;
use Commander\EventStore\Exception\EventStoreException;
use Commander\ID\Identifier;
use Exception;
use PDO;

final class PDOEventStore implements EventStore
{
    private PDO $pdo;
    private EventMap $map;
    private EventContext $context;

    public function __construct(PDO $pdo, EventMap $map, EventContext $context)
    {
        $this->pdo = $pdo;
        $this->map = $map;
        $this->context = $context;
    }

    /**
     * @throws EventStoreException
     */
    public function store(Messages $messages): void
    {
        try {
            $this->pdo->beginTransaction();

            $sql = <<<QUERY
                INSERT INTO `events` (
                    `event_id`, 
                    `correlation_id`, 
                    `causation_id`, 
                    `event_stream_id`, 
                    `event_stream_version`, 
                    `occurred_on`, 
                    `topic`, 
                    `version`, 
                    `payload`
                ) 
                VALUES (
                    :event_id, 
                    :correlation_id, 
                    :causation_id, 
                    :event_stream_id, 
                    :event_stream_version, 
                    :occurred_on, 
                    :topic, 
                    :version, 
                    :payload
                );
            QUERY;

            $statement = $this->pdo->prepare($sql);

            foreach ($messages as $message) {
                $statement->execute([
                    'event_id'             => $message->getId()->asString(),
                    'correlation_id'       => $this->context->getCurrentCorrelationId()->asString(),
                    'causation_id'         => $this->context->getCurrentCausationId()->asString(),
                    'event_stream_id'      => $message->getEventStreamId()->asString(),
                    'event_stream_version' => $message->getEventStreamVersion(),
                    'occurred_on'          => $message->getOccurredOn()->format('Y-m-d H:i:s'),
                    'topic'                => $message->getEvent()->getTopic(),
                    'version'              => $message->getEvent()->getVersion(),
                    'payload'              => json_encode($message->getEvent()->getPayload(), JSON_THROW_ON_ERROR)
                ]);
            }

            $this->pdo->commit();
        } catch (Exception $exception) {
            $this->pdo->rollBack();
            throw new EventStoreException('Could not store events', 0, $exception);
        }
    }

    /**
     * @throws EventStoreException
     */
    public function load(Identifier $id): Messages
    {
        $query = <<<QUERY
            SELECT 
                * 
            FROM 
                `events` 
            WHERE 
                `event_stream_id` = :event_stream_id;
        QUERY;

        $messages = [];

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute([
                'event_stream_id' => $id->asString(),
            ]);

            while ($row = $statement->fetch()) {
                $messages[] = $this->map->reconstitute($row);
            }
        } catch (Exception $exception) {
            throw new EventStoreException('Could not load events', 0, $exception);
        }

        if (count($messages) === 0) {
            throw new EventStoreException('No events for event stream found');
        }

        return Messages::from($messages);
    }
}
