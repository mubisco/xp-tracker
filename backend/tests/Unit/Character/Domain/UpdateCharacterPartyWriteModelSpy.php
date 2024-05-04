<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Character\Domain;

use XpTracker\Character\Domain\Character;
use XpTracker\Character\Domain\UpdateCharacterPartyWriteModel;

final class UpdateCharacterPartyWriteModelSpy implements UpdateCharacterPartyWriteModel
{
    public ?Character $updatedCharacter = null;
    public int $timesCalled = 0;

    public function updateCharacterParty(Character $character): void
    {
        $this->timesCalled++;
        $this->updatedCharacter = $character;
    }
}
