<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\Command;

use Commander\Integration\Stub\EventStream\Exception\UserNotFoundException;
use Commander\Integration\Stub\EventStream\Exception\UserNotSavedException;
use Commander\Integration\Stub\EventStream\UserEventStream;
use Commander\Integration\Stub\EventStream\UserId;
use Commander\Integration\Stub\EventStream\UserRepository;
use Commander\Integration\Stub\Command\Exception\UserAlreadyExistsException;

final class RegisterUserCommandHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UserNotSavedException
     * @throws UserAlreadyExistsException
     */
    public function __invoke(RegisterUserCommand $command): void
    {
        $id = $command->getUserId();
        $name = $command->getName();

        $this->assertUserDoesNotExists($id);

        $user = UserEventStream::register($id, $name);

        $this->repository->save($user);
    }

    /**
     * @throws UserAlreadyExistsException
     */
    private function assertUserDoesNotExists(UserId $id): bool
    {
        try {
            $this->repository->load($id);
        } catch (UserNotFoundException $exception) {
            return true;
        }

        throw new UserAlreadyExistsException('User already exists');
    }
}
