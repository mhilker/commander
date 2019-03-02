<?php

declare(strict_types=1);

namespace MHilker\CQRS\Query;

interface QueryBus
{
    public function execute($query);
}
