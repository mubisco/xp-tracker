<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class FailingPartyRepositoryStub implements PartyRepository
{
    public function byUlid(SharedUlid $ulid): Party
    {
        throw new PartyNotFoundException();
    }
}
