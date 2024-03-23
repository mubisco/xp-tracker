<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class CharacterJoined implements DomainEvent
{
    private readonly DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $characterId,
        public readonly string $partyId,
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->characterId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
