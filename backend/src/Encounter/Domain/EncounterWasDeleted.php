<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class EncounterWasDeleted implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $encounterUlid
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->encounterUlid;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
