<?php

declare(strict_types=1);

namespace MHilker\CQRS\Query;

use MHilker\CQRS\Query\Exception\InvalidQueryClassException;
use MHilker\CQRS\Query\Exception\QueryHandlerNotFoundException;

class QueryHandlers
{
    private $handlers = [];

    public function addHandler(callable $queryHandler, string $queryClass): void
    {
        if (class_exists($queryClass) === false) {
            throw new InvalidQueryClassException();
        }

        $this->handlers[$queryClass] = $queryHandler;
    }

    public function getQueryHandlerForClass(string $queryClass): callable
    {
        if (isset($this->handlers[$queryClass]) === false) {
            throw new QueryHandlerNotFoundException();
        }

        return $this->handlers[$queryClass];
    }
}
