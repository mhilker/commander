<?php

declare(strict_types=1);

namespace Commander\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class Messages implements IteratorAggregate, Countable
{
    /**
     * @var Message[]
     */
    private array $messages = [];

    private function __construct(iterable $messages)
    {
        foreach ($messages as $message) {
            $this->add($message);
        }
    }

    public static function from(iterable $messages = []): Messages
    {
        return new self($messages);
    }

    private function add(Message $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return Traversable | Message[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->messages);
    }

    public function count(): int
    {
        return count($this->messages);
    }

    public function getEvents()
    {
        $events = [];
        foreach ($this->messages as $message) {
            $events[] = $message->getEvent();
        }
        return Events::from($events);
    }
}
