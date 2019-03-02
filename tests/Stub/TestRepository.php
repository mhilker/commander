<?php

declare(strict_types=1);

namespace Commander\Stub;

use Commander\Aggregate\AggregateRepository;

class TestRepository
{
    private $repository;

    public function __construct(AggregateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(TestAggregate $test): void
    {
        $this->repository->save($test);
    }

    public function load(TestId $id): TestAggregate
    {
        return $this->repository->load($id);
    }
}
