<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Event;

use XpTracker\Character\Domain\CharacterRepository;
use XpTracker\Character\Domain\Experience;
use XpTracker\Character\Domain\UpdateCharacterPartyWriteModel;
use XpTracker\Encounter\Domain\EncounterWasSolved;
use XpTracker\Encounter\Domain\Party\PartyLevelWasUpdated;
use XpTracker\Shared\Application\EventHandlerInterface;
use XpTracker\Shared\Domain\Event\EventBus;
use XpTracker\Shared\Domain\Identity\SharedUlid;

final class AddExperiencePointsWhenEncounterWasResolvedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly CharacterRepository $repository,
        private readonly UpdateCharacterPartyWriteModel $writeModel,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(EncounterWasSolved $event): void
    {
        $ulid = SharedUlid::fromString($event->partyUlid);
        $characters = $this->repository->ofPartyId($ulid);
        $experience = $this->divideExperience($event->totalXp, count($characters));
        $levels = [];
        foreach ($characters as $character) {
            $character->addExperience($experience);
            $levels[] = $character->level();
            $this->writeModel->updateCharacterParty($character);
        }
        $partyUpdatedEvent = new PartyLevelWasUpdated(
            encounterUlid: $event->encounterUlid,
            partyUlid: $event->partyUlid,
            charactersLevel: $levels
        );
        $this->eventBus->publish([$partyUpdatedEvent]);
    }

    private function divideExperience(int $totalXp, int $totalCharacters): Experience
    {
        $xp = (int) round($totalXp / $totalCharacters);
        return Experience::fromInt($xp);
    }
}
