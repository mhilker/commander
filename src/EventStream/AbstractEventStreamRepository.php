<?php

declare(strict_types=1);

namespace Commander\EventStream;

use Commander\EventStream\Exception\EventStreamNotFoundException;
use Commander\EventStream\Exception\EventStreamNotSavedException;
use Commander\Event\EventPublisher;
use Commander\Event\Messages;
use Commander\EventStore\EventStore;
use Commander\ID\Identifier;
use Exception;

abstract class AbstractEventStreamRepository implements EventStreamRepository
{
    private EventStore $store;
    private EventPublisher $publisher;

    public function __construct(EventStore $store, EventPublisher $publisher)
    {
        $this->store = $store;
        $this->publisher = $publisher;
    }

    /**
     * @throws EventStreamNotSavedException
     */
    public function save(AbstractEventStream $stream): void
    {
        try {
            $messages = $stream->popEvents();
            $this->store->store($messages);
        } catch (Exception $exception) {
            throw new EventStreamNotSavedException('Could not save event stream', 0, $exception);
        }

        $this->publisher->publish($messages);
    }

    /**
     * @throws EventStreamNotFoundException
     */
    public function load(Identifier $id): AbstractEventStream
    {
        try {
            $messages = $this->store->load($id);
            return $this->createStreamWithMessages($messages);
        } catch (Exception $exception) {
            throw new EventStreamNotFoundException('Could not load event stream', 0, $exception);
        }
    }

    abstract protected function createStreamWithMessages(Messages $messages): AbstractEventStream;
}
