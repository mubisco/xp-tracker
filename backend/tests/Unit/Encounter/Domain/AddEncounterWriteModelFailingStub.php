<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use XpTracker\Encounter\Domain\AddEncounterWriteModel;
use XpTracker\Encounter\Domain\Encounter;
use XpTracker\Encounter\Domain\EncounterWriteModelException;

final class AddEncounterWriteModelFailingStub implements AddEncounterWriteModel
{
    public function store(Encounter $encounter): void
    {
        throw new EncounterWriteModelException();
    }
}
