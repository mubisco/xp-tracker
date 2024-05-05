<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class PartyOM
{
    private SharedUlid $ulid;
    private string $name;

    public static function aBuilder(): static
    {
        return new static();
    }

    private function __construct()
    {
        $this->ulid = SharedUlid::fromEmpty();
        $this->name = 'Comando G';
    }

    public function withUlid(string $ulid): static
    {
        $this->ulid = SharedUlid::fromString($ulid);
        return $this;
    }

    public function build(): Party
    {
        $events = [new PartyWasCreated($this->ulid->ulid(), $this->name)];
        $collection = EventCollection::fromValues($this->ulid->ulid(), $events);
        return BasicParty::fromEvents($collection);
    }
}
