<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Aggregate\AggregateId;
use Commander\Event\Event;
use Commander\Event\EventHandler;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;
use Commander\Stub\Aggregate\UserRepository;

final class DisableUsersWithBlacklistedNamesPolicy implements EventHandler
{
    private const BLACKLISTED_NAMES = [
        'Test',
    ];

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Event $event): void
    {
        switch ($event->getTopic()) {
            case UserRegisteredEvent::TOPIC:
                $this->checkRegisteredUserName($event);
                break;
            case UserRenamedEvent::TOPIC:
                $this->checkRenamedUserName($event);
                break;
        }
    }

    private function checkRegisteredUserName(UserRegisteredEvent $event): void
    {
        $this->checkUserName($event->getAggregateId(), $event->getName());
    }

    private function checkRenamedUserName(UserRenamedEvent $event): void
    {
        $this->checkUserName($event->getAggregateId(), $event->getName());
    }

    private function checkUserName(AggregateId $id, UserName $name): void
    {
        if (in_array($name->asString(), self::BLACKLISTED_NAMES, false) === false) {
            return;
        }

        $user = $this->repository->load($id);
        $user->disable();
        $this->repository->save($user);
    }
}
