<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

use Commander\Aggregate\AggregateRepository;

class AggregateUserRepository implements UserRepository
{
    private AggregateRepository $repository;

    public function __construct(AggregateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(UserAggregate $user): void
    {
        $this->repository->save($user);
    }

    public function load(UserId $id): UserAggregate
    {
        return $this->repository->load($id);
    }
}
