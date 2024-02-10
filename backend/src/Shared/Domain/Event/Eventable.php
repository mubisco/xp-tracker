<?php

namespace XpTracker\Shared\Domain\Event;

interface Eventable
{
    /**
     * @return DomainEvent[]
     */
    public function pullEvents(): array;
}
