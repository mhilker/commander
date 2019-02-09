<?php

declare(strict_types=1);

namespace MHilker\CQRS\Query;

use MHilker\CQRS\Query\Exception\QueryHandlerNotFoundException;

class DirectQueryBus implements QueryBusInterface
{
    private $handlers;

    /**
     * @param QueryHandlers $handlers
     */
    public function __construct(QueryHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param object $query
     * @return mixed
     * @throws QueryHandlerNotFoundException
     */
    public function execute($query)
    {
        $queryClass = get_class($query);

        $queryHandler = $this->handlers->getQueryHandlerForClass($queryClass);
        return $queryHandler($query);
    }
}
