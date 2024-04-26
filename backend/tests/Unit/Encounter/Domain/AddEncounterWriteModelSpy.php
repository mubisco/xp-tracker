<?php

declare(strict_types=1);

namespace XpTracker\Tests\Unit\Encounter\Domain;

use XpTracker\Encounter\Domain\AddEncounterWriteModel;
use XpTracker\Encounter\Domain\Encounter;

final class AddEncounterWriteModelSpy implements AddEncounterWriteModel
{
    public ?Encounter $addedEncounter = null;

    public function store(Encounter $encounter): void
    {
        $this->addedEncounter = $encounter;
    }
}
