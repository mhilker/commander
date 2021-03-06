<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\Event;

use Commander\Command\CommandPublisher;
use Commander\Event\Event;
use Commander\Event\EventHandler;
use Commander\Integration\Stub\EventStream\UserId;
use Commander\Integration\Stub\EventStream\UserName;
use Commander\Integration\Stub\Command\RegisterUserCommand;

final class RegisterUserWhenUserWasDisabledPolicy implements EventHandler
{
    private CommandPublisher $commandPublisher;

    public function __construct(CommandPublisher $commandPublisher)
    {
        $this->commandPublisher = $commandPublisher;
    }

    public function handle(Event $event): void
    {
        switch ($event->getTopic()) {
            case UserDisabledEvent::TOPIC:
                /** @var UserDisabledEvent $event */
                $this->handleUserDisabled($event);
                break;
        }
    }

    private function handleUserDisabled(UserDisabledEvent $event): void
    {
        $userId = UserId::generateV4();
        $name = UserName::from('Disabled User');

        $command = new RegisterUserCommand($userId, $name);

        $this->commandPublisher->publish($command);
    }
}
