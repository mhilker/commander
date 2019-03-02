<?php

declare(strict_types=1);

namespace Commander\Query;

use Commander\Query\Exception\InvalidQueryClassException;
use Commander\Query\Exception\QueryHandlerNotFoundException;

class QueryHandlers
{
    private $handlers = [];

    private function __construct(array $handlers)
    {
        foreach ($handlers as $queryClass => $queryHandler) {
            $this->add($queryClass, $queryHandler);
        }
    }

    public static function from(array $handlers = []): QueryHandlers
    {
        return new self($handlers);
    }

    public function add(string $queryClass, callable $queryHandler): void
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
