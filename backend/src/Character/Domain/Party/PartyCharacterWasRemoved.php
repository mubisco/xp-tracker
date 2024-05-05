<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain\Party;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class PartyCharacterWasRemoved implements DomainEvent
{
    private readonly DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $partyId,
        public readonly string $characterId
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function id(): string
    {
        return $this->partyId;
    }
}
