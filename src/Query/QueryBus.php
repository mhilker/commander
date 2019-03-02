<?php

declare(strict_types=1);

namespace Commander\Query;

interface QueryBus
{
    /**
     * @param mixed $query
     * @return mixed
     */
    public function execute($query);
}
