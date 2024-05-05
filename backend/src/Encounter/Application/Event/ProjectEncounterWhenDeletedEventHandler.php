<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Application\Event;

use XpTracker\Encounter\Domain\EncounterRepository;
use XpTracker\Encounter\Domain\EncounterWasDeleted;
use XpTracker\Encounter\Domain\Projection\ResolvedEncounterProjection;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;
use XpTracker\Shared\Application\EventHandlerInterface;

final class ProjectEncounterWhenDeletedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly EncounterRepository $repository,
        private readonly ResolvedEncounterProjection $projection
    ) {
    }
    public function __invoke(EncounterWasDeleted $event): void
    {
        $encounterId = $this->parseEncounterUlid($event->encounterUlid);
        $encounter = $this->repository->byEncounterId($encounterId);
        $this->projection->projectResolved($encounter);
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
