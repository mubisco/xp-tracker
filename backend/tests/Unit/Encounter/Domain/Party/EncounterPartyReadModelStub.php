<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain\Party;

use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\EncounterPartyReadModel;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class EncounterPartyReadModelStub implements EncounterPartyReadModel
{
    public function __construct(private readonly SharedUlid $partyUlid)
    {
    }

    public function byPartyId(SharedUlid $partyUlid): EncounterParty
    {
        return new EncounterParty(partyUlid: $this->partyUlid->ulid(), charactersLevel: [1, 2]);
    }
}
