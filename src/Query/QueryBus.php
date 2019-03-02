<?php

declare(strict_types=1);

namespace Commander\Query;

interface QueryBus
{
    public function execute($query);
}
