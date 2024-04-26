<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Command;

use XpTracker\Encounter\Domain\AddEncounterWriteModel;
use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Shared\Application\CommandHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;

final class CreateEncounterCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AddEncounterWriteModel $writeModel,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(CreateEncounterCommand $command): void
    {
        $encounter = BasicEncounter::create($command->ulid, $command->name);
        $this->writeModel->store($encounter);
        $this->eventBus->publish($encounter->pullEvents());
    }
}
