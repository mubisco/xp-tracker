<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use XpTracker\Character\Domain\Party\CreatePartyWriteModel;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyAlreadyExistsException;

final class FailingCreatePartyWriteModelStub implements CreatePartyWriteModel
{
    public function createParty(Party $party): void
    {
        throw new PartyAlreadyExistsException();
    }
}
