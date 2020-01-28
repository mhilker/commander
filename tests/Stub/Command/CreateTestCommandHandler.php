<?php

declare(strict_types=1);

namespace Commander\Stub;

class CreateTestCommandHandler
{
    private $repository;

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
