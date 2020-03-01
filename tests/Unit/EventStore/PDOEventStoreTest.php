<?php

declare(strict_types=1);

namespace Commander\Unit\EventStore;

use Commander\EventStore\EventContext;
use Commander\EventStore\EventMap;
use Commander\EventStore\PDOEventStore;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\EventStore\PDOEventStore
 */
final class PDOEventStoreTest extends TestCase
{
    public function test(): void
    {
        $pdo = $this->createMock(PDO::class);
        $map = $this->createMock(EventMap::class);
        $context = new EventContext();

        $store = new PDOEventStore($pdo, $map, $context);
    }
}
