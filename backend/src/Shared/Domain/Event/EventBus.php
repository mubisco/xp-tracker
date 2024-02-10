<?php

namespace XpTracker\Shared\Domain\Event;

interface EventBus
{
    /** @param DomainEvent[] $events */
    public function publish(array $events): void;
}
