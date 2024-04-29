<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Level;

use XpTracker\Encounter\Domain\Level\LevelTag;

final class EncounterLevel
{
    public static function empty(): static
    {
        return new static(PartyTresholds::fromEmpty(), MonstersXp::fromEmpty());
    }
    public static function fromPartyLevels(): static
    {
        return new static(PartyTresholds::fromIntValues([1, 2]), MonstersXp::fromEmpty());
    }
    /**
     * @param array<int,int> $levels
     * @param array<int,int> $monstersXp
     */
    public static function fromValues(array $levels, array $monstersXp): EncounterLevel
    {
        return new static(PartyTresholds::fromIntValues($levels), MonstersXp::fromIntValues($monstersXp));
    }

    private function __construct(
        private readonly PartyTresholds $partyTresholds,
        private readonly MonstersXp $monstersXp
    ) {
    }

    public function level(): LevelTag
    {
        if ($this->partyTresholds->empty()) {
            return LevelTag::UNNASSIGNED;
        }
        if ($this->monstersXp->empty()) {
            return LevelTag::EMPTY;
        }
        return $this->calculateTag();
    }

    private function calculateTag(): LevelTag
    {
        $index = $this->calculateIndex();
        if ($index === 0) {
            return LevelTag::EASY;
        }
        if ($index === 1) {
            return LevelTag::MEDIUM;
        }
        if ($index === 2) {
            return LevelTag::HARD;
        }
        return LevelTag::DEADLY;
    }

    private function calculateIndex(): int
    {
        $monsterPoints = $this->monstersXp->totalXp();
        $tresholds = $this->partyTresholds->tresholds();
        $tresholdIndex = 0;
        for ($i = 0; $i < count($tresholds); $i++) {
            $currentTreshold = $tresholds[$i];
            if ($monsterPoints < $currentTreshold) {
                continue;
            }
            $tresholdIndex = $i;
        }
        return $tresholdIndex;
    }
}
