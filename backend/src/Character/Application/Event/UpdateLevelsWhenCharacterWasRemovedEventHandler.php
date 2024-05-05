<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Event;

use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\UpdateCharacterPartyWriteModel;
use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\EncounterWasDeleted;
use XpTracker\Encounter\Domain\Party\PartyLevelWasUpdated;
use XpTracker\Shared\Application\EventHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;
use XpTracker\Shared\Domain\Identity\SharedUlid;

class UpdateLevelsWhenCharacterWasRemovedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly EncounterRepository $encounterRepository,
        private readonly CharacterRepository $repository,
        private readonly UpdateCharacterPartyWriteModel $writeModel,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(EncounterWasDeleted $event): void
    {
        $encounterUlid = SharedUlid::fromString($event->encounterUlid);
        $encounter = $this->encounterRepository->byEncounterId($encounterUlid);
        $partyUlid = SharedUlid::fromString($encounter->partyId());
        $characters = $this->repository->ofPartyId($partyUlid);
        $levels = [];
        foreach ($characters as $character) {
            $levels[] = $character->level();
        }
        $partyUpdatedEvent = new PartyLevelWasUpdated(
            encounterUlid: $event->encounterUlid,
            partyUlid: $partyUlid->ulid(),
            charactersLevel: $levels
        );
        $this->eventBus->publish([$partyUpdatedEvent]);
    }
}
