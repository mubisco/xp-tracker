<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Party;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class PartyWasUnassigned implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    /**
     * @param array<int,int> $charactersLevel
     */
    public function __construct(
        public readonly string $encounterUlid,
        public readonly string $partyUlid,
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
