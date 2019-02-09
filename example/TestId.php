<?php

declare(strict_types=1);

namespace MHilker\Example;

use MHilker\EventSourcing\AggregateId;

class TestId implements AggregateId
{
    private $id;

    public function __construct(string $id)
    {
        if (mb_strlen($id) === 0) {
            throw new \Exception();
        }
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id;
    }
}
