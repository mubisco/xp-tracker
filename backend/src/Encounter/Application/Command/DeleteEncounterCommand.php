<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

final class DeleteEncounterCommand
{
    public function __construct(public readonly string $encounterId)
    {
    }
}
