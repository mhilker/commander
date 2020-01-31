<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

use Commander\Aggregate\AbstractAggregate;
use Commander\Aggregate\AggregateId;
use Commander\Event\Event;
use Commander\Stub\Event\UserRegisteredEvent;
use Commander\Stub\Event\UserRenamedEvent;

class UserAggregate extends AbstractAggregate
{
    private AggregateId $aggregateId;

    private UserName $name;

    public static function register(AggregateId $id, UserName $name): UserAggregate
    {
        $user = new self(null);
        $user->record(UserRegisteredEvent::occur($id, $name));
        return $user;
    }

    protected function applyUserRegistered(UserRegisteredEvent $event): void
    {
        $this->aggregateId = $event->getAggregateId();
        $this->name = $event->getName();
    }

    public function rename(UserName $newName): void
    {
        if ($this->name->notEqual($newName)) {
            $this->record(UserRenamedEvent::occur($this->aggregateId, $newName));
        }
    }

    protected function applyUserRenamed(UserRenamedEvent $event): void
    {
        $this->name = $event->getName();
    }

    protected function apply(Event $event): void
    {
        switch ($event->getTopic()) {
            case UserRegisteredEvent::TOPIC:
                /** @var UserRegisteredEvent $event */
                $this->applyUserRegistered($event);
                break;
            case UserRenamedEvent::TOPIC:
                /** @var UserRenamedEvent $event */
                $this->applyUserRenamed($event);
                break;
        }
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }
}
