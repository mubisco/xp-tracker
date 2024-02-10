<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class CharacterWasCreated implements DomainEvent
{
    private readonly DateTimeImmutable $occurredOn;
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $experiencePoints,
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
