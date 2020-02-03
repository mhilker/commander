<?php

declare(strict_types=1);

namespace Commander\Aggregate;

use Commander\Event\Event;
use Commander\Event\Message;
use Commander\Event\Messages;
use Commander\Util\Identifier;

abstract class AbstractAggregate
{
    private int $version = 0;
    private array $messages = [];

    protected function __construct(?Messages $messages)
    {
        if ($messages !== null) {
            foreach ($messages as $message) {
                $this->apply($message->getEvent());
            }
        }
    }

    public static function from(Messages $messages): self
    {
        return new static($messages);
    }

    public function record(Event $event): void
    {
        $this->apply($event);
        $this->messages[] = Message::wrap($this->getAggregateId(), $this->version, $event);
    }

    private function apply(Event $event): void
    {
        $this->version++;
        $this->dispatch($event);
    }

    abstract protected function dispatch(Event $event): void;

    abstract public function getAggregateId(): Identifier;

    public function popEvents(): Messages
    {
        $messages = Messages::from($this->messages);
        $this->messages = [];
        return $messages;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
