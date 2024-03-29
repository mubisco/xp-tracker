<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\AddCharacterWriteModelException;
use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\UpdateCharacterPartyWriteModel;

final class FailingUpdateCharacterPartyWriteModelStub implements UpdateCharacterPartyWriteModel
{
    public function updateCharacterParty(Character $character): void
    {
        throw new AddCharacterWriteModelException();
    }
}
