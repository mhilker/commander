<?php

declare(strict_types=1);

namespace MHilker\CQRS\Query;

interface QueryBusInterface
{
    public function execute($query);
}
