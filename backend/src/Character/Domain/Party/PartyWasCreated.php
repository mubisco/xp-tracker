<?php

declare(strict_types=1);

namespace XpTracker\Character\Domain\Party;

use DateTimeImmutable;
use XpTracker\Shared\Domain\Event\DomainEvent;

final class PartyWasCreated implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $aggregateId,
        public readonly string $partyName
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function id(): string
    {
        return $this->aggregateId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
