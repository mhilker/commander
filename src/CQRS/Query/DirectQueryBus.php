<?php

declare(strict_types=1);

namespace MHilker\CQRS\Query;

class DirectQueryBus implements QueryBus
{
    private $handlers;

    public function __construct(QueryHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    public function execute($query)
    {
        $queryClass = get_class($query);

        $queryHandler = $this->handlers->getQueryHandlerForClass($queryClass);

        return $queryHandler($query);
    }
}
