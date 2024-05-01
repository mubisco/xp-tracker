<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\EncounterWriteModelException;
use XpTracker\Encounter\Domain\UpdateEncounterWriteModel;

final class UpdateEncounterWriteModelFailingStub implements UpdateEncounterWriteModel
{
    public function update(Encounter $update): void
    {
        throw new EncounterWriteModelException();
    }
}
