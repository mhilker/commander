<?php

declare(strict_types=1);

namespace MHilker\CQRS\Query;

interface QueryBusInterface
{
    /**
     * @param object $query
     * @return mixed
     */
    public function execute($query);
}
