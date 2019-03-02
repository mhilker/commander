<?php

declare(strict_types=1);

namespace Commander\Query;

class DirectQueryBus implements QueryBus
{
    /** @var QueryHandlers */
    private $handlers;

    public function __construct(QueryHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param mixed $query
     * @return mixed
     */
    public function execute($query)
    {
        $queryClass = get_class($query);

        $queryHandler = $this->handlers->getQueryHandlerForClass($queryClass);

        return $queryHandler($query);
    }
}
