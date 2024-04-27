<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

use XpTracker\Encounter\Domain\Monster\WrongMonsterValueException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Application\CommandHandlerInterface;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

final class AddMonsterToEncounterCommandHandler implements CommandHandlerInterface
{
    public function __invoke(AddMonsterToEncounterCommand $command): void
    {
        try {
            SharedUlid::fromString($command->encounterUlid);
        } catch (WrongUlidValueException $e) {
            throw new WrongEncounterUlidException($e->getMessage(), $e->getCode(), $e);
        }
        throw new WrongMonsterValueException();
    }
}
