<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain;

use XpTracker\Shared\Domain\AggregateRoot;
use XpTracker\Shared\Domain\Identity\WrongUlidValueException;

final class BasicEncounter extends AggregateRoot implements Encounter
{
    private EncounterName $name;

    public static function create(string $ulid, string $name): static
    {
        try {
            $encounter = new static($ulid);
            $event = new EncounterWasCreated($ulid, $name);
            $encounter->apply($event);
            return $encounter;
        } catch (WrongUlidValueException $e) {
            throw new WrongEncounterUlidException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function collect(): array
    {
        return [
            'name' => $this->name->value()
        ];
    }

    protected function applyEncounterWasCreated(EncounterWasCreated $event): void
    {
        $this->name = EncounterName::fromString($event->name);
    }
}
