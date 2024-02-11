<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Shared\Domain\Event;

use XpTracker\Shared\Domain\Event\DomainEvent;
use XpTracker\Shared\Domain\Event\EventBus;

final class EventBusSpy implements EventBus
{
    /** @var DomainEvent[] $publishedEvents */
    public array $publishedEvents = [];

    public function publish(array $events): void
    {
        $this->publishedEvents = $events;
    }
}
