<?php

declare(strict_types=1);

namespace Commander\Stub\Aggregate;

use Commander\Aggregate\AggregateRepository;
use Commander\Stub\Aggregate\TestAggregate;
use Commander\Stub\Aggregate\TestId;

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
