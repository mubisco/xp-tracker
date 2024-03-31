<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\FullPartyReadModel;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class NotFoundFullPartyReadModelStub implements FullPartyReadModel
{
    public function byUlid(SharedUlid $ulid): array
    {
        throw new PartyNotFoundException();
    }
}
