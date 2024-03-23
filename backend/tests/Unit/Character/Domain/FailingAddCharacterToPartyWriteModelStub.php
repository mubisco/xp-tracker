<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Party\AddCharacterToPartyWriteModel;
use XpTracker\Character\Domain\Party\Party;
use XpTracker\Character\Domain\Party\PartyWriteModelException;

final class FailingAddCharacterToPartyWriteModelStub implements AddCharacterToPartyWriteModel
{
    public function updateCharacters(Party $party): void
    {
        throw new PartyWriteModelException();
    }
}
