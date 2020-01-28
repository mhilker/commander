<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\TestAggregate;
use Commander\Stub\Aggregate\TestRepository;

class CreateTestCommandHandler
{
    private TestRepository $repository;

    public function __construct(TestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateTestCommand $command): void
    {
        $id = $command->getId();
        $name = $command->getName();

        $test = TestAggregate::create($id, $name);

        $this->repository->save($test);
    }
}
