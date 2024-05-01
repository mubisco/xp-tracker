<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Event;

use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Encounter\Domain\Projection\CreatedEncounterProjection;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Application\EventHandlerInterface;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

final class ProjectEncounterWhenCreatedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly EncounterRepository $repository,
        private readonly CreatedEncounterProjection $projection
    ) {
    }
    public function __invoke(EncounterWasCreated $event): void
    {
        $encounterId = $this->parseEncounterUlid($event->id());
        $encounter = $this->repository->byEncounterId($encounterId);
        $this->projection->projectCreated($encounter);
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
