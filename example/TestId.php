<?php

declare(strict_types=1);

namespace MHilker\Example;

use MHilker\CQRS\Aggregate\AggregateId;

class TestId implements AggregateId
{
    private $id;

    public function __construct(string $id)
    {
        if ($id === '') {
            throw new \Exception();
        }
        $this->id = $id;
    }

    public static function generate(): TestId
    {
        return new self(UUID::v4());
    }

    public function asString(): string
    {
        return $this->id;
    }
}
