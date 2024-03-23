<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain\Party;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class CharacterWasAdded implements DomainEvent
{
    private readonly DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $id,
        public readonly string $addedCharacterId
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
