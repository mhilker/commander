<?php

declare(strict_types=1);

namespace Commander\Aggregate;

use Commander\Aggregate\Exception\AggregateNotFoundException;
use Commander\Aggregate\Exception\AggregateNotSavedException;
use Commander\Event\EventPublisher;
use Commander\Event\Messages;
use Commander\EventStore\EventStore;
use Commander\Identifier;
use Exception;

abstract class AbstractEventStoreAggregateRepository implements AggregateRepository
{
    private EventStore $eventStore;
    private EventPublisher $eventPublisher;

    public function __construct(EventStore $eventStore, EventPublisher $eventPublisher)
    {
        $this->eventStore = $eventStore;
        $this->eventPublisher = $eventPublisher;
    }

    /**
     * @throws AggregateNotSavedException
     */
    public function save(AbstractAggregate $aggregate): void
    {
        try {
            $messages = $aggregate->popEvents();
            $this->eventStore->store($messages);
        } catch (Exception $exception) {
            throw new AggregateNotSavedException('Could not save aggregate', 0, $exception);
        }

        $this->eventPublisher->publish($messages->getEvents());
    }

    /**
     * @throws AggregateNotFoundException
     */
    public function load(Identifier $id): AbstractAggregate
    {
        try {
            $messages = $this->eventStore->load($id);
            return $this->createAggregateWithMessages($messages);
        } catch (Exception $exception) {
            throw new AggregateNotFoundException('Could not load aggregate', 0, $exception);
        }
    }

    abstract protected function createAggregateWithMessages(Messages $messages): AbstractAggregate;
}
