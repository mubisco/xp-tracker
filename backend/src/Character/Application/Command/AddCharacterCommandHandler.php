<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Command;

use XpTracker\Character\Application\Domain\AddCharacterWriteModel;
use XpTracker\Character\Application\Domain\BasicCharacter;
use XpTracker\Shared\Domain\Event\EventBus;

class AddCharacterCommandHandler
{
    public function __construct(
        private readonly AddCharacterWriteModel $writeModel,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(AddCharacterCommand $command): void
    {
        $character = BasicCharacter::create(
            $command->ulid,
            $command->characterName,
            $command->experiencePoints
        );
        $this->writeModel->add($character);
        $this->eventBus->publish($character->pullEvents());
    }
}
