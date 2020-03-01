<?php

declare(strict_types=1);

namespace Commander\Unit\EventStream;

use Commander\Event\EventPublisher;
use Commander\EventStore\EventStore;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Commander\EventStream\AbstractEventStreamRepository
 */
final class AbstractEventStreamRepositoryTest extends TestCase
{
    public function test(): void
    {
        $store = $this->createMock(EventStore::class);
        $publisher = $this->createMock(EventPublisher::class);
        $repository = new StubEventStreamRepository($store, $publisher);
    }
}
