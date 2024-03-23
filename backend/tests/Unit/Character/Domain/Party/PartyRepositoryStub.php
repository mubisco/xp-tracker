<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class PartyRepositoryStub implements PartyRepository
{
    public function byUlid(SharedUlid $ulid): Party
    {
        $events = [new PartyWasCreated('01HSNTRV7G6R9AJCEB87ZXF7EB', 'Comando G')];
        $collection = EventCollection::fromValues('01HSNTRV7G6R9AJCEB87ZXF7EB', $events);
        return BasicParty::fromEvents($collection);
    }
}
