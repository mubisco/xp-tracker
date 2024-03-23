<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyRepository;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class PartyRepositoryStub implements PartyRepository
{
    public function byUlid(SharedUlid $ulid): Party
    {
        return PartyOM::aBuilder()->build();
    }
}
