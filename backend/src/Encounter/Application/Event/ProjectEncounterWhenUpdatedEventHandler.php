<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Event;

use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\EncounterWasUpdated;
use XpTracker\Encounter\Domain\Projection\UpdatedEncounterProjection;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;
use XpTracker\Shared\Application\EventHandlerInterface;

final class ProjectEncounterWhenUpdatedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly EncounterRepository $repository,
        private readonly UpdatedEncounterProjection $projection
    ) {
    }
    public function __invoke(EncounterWasUpdated $event): void
    {
        $encounterId = $this->parseEncounterUlid($event->encounterId);
        $encounter = $this->repository->byEncounterId($encounterId);
        $this->projection->projectUpdate($encounter);
    }

    private function parseEncounterUlid(string $ulid): SharedUlid
    {
        try {
            return SharedUlid::fromString($ulid);
        } catch (WrongUlidValueException $e) {
            throw new WrongEncounterUlidException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
