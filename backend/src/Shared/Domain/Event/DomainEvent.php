<?php

namespace XpTracker\Shared\Domain\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function id(): string;
    public function occurredOn(): DateTimeImmutable;
}
