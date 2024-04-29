<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Level;

use XpTracker\Encounter\Domain\Level\LevelTag;

final class EncounterLevel
{
    public static function empty(): static
    {
        return new static([], []);
    }
    public static function fromPartyLevels(): static
    {
        return new static([1, 2], []);
    }
    /**
     * @param array<int,int> $levels
     * @param array<int,int> $monstersXp
     */
    public static function fromValues(array $levels, array $monstersXp): EncounterLevel
    {
        return new static($levels, $monstersXp);
    }

    private const TRESHOLDS = [
        [0, 0, 0, 0],
        [25, 50, 75, 100],
        [50, 100, 150, 200],
        [75, 150, 225, 400],
        [125, 250, 375, 500],
        [250, 500, 750, 1100],
        [300, 600, 900, 1400],
        [350, 750, 1100, 1700],
        [450, 900, 1400, 2100],
        [550, 1100, 1600, 2400],
        [600, 1200, 1900, 2800],
        [800, 1600, 2400, 3600],
        [1000, 2000, 3000, 4500],
        [1100, 2200, 3400, 5100],
        [1250, 2500, 3800, 5700],
        [1400, 2800, 4300, 6400],
        [1600, 3200, 4800, 7200],
        [2000, 3900, 5900, 8800],
        [2100, 4200, 6300, 9500],
        [2400, 4900, 7300, 10900],
        [2800, 5700, 8500, 12700]
    ];
    /**
     * @param array<int,int> $levels
     * @param array<int,int> $monstersXp
     */
    private function __construct(
        private readonly array $levels,
        private readonly array $monstersXp
    ) {
    }
    public function level(): LevelTag
    {
        if (empty($this->levels)) {
            return LevelTag::UNNASSIGNED;
        }
        if (empty($this->monstersXp)) {
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

    private function monsterValue(): int
    {
        $multiplier = $this->monsterMultiplier();
        $total = array_sum($this->monstersXp);
        return (int) floor($total * $multiplier);
    }

    private function monsterMultiplier(): float
    {
        $monsterQuantity = count($this->monstersXp);
        if ($monsterQuantity > 14) {
            return 4;
        }
        if ($monsterQuantity > 10) {
            return 3;
        }
        if ($monsterQuantity > 6) {
            return 2.5;
        }
        if ($monsterQuantity > 2) {
            return 2;
        }
        if ($monsterQuantity > 1) {
            return 1.5;
        }
        return 1;
    }

    private function calculateIndex(): int
    {
        $monsterPoints = $this->monsterValue();
        $tresholds = $this->getPartyTresholds();
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
    /**
     * @return array<int,int>
     */
    private function getPartyTresholds(): array
    {
        $baseTresholds = [0, 0, 0, 0];
        foreach ($this->levels as $characterLevel) {
            $characterTresholds = self::TRESHOLDS[$characterLevel];
            $baseTresholds[0] += $characterTresholds[0];
            $baseTresholds[1] += $characterTresholds[1];
            $baseTresholds[2] += $characterTresholds[2];
            $baseTresholds[3] += $characterTresholds[3];
        }
        return $baseTresholds;
    }
}
