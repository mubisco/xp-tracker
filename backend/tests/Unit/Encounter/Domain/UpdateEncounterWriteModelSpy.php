<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\UpdateEncounterWriteModel;

final class UpdateEncounterWriteModelSpy implements UpdateEncounterWriteModel
{
    public ?Encounter $updatedEncounter = null;

    public function update(Encounter $update): void
    {
        $this->updatedEncounter = $update;
    }
}
