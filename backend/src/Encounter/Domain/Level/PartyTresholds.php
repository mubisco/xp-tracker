<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Level;

final class PartyTresholds
{
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

    public static function fromEmpty(): static
    {
        return new static([]);
    }
    /**
     * @param array<int,int> $values
     */
    public static function fromIntValues(array $values): static
    {
        return new static($values);
    }
    /**
     * @param array<int,int> $values
     */
    private function __construct(private readonly array $values)
    {
    }

    public function empty(): bool
    {
        return count($this->values) === 0;
    }
    /**
     * @return array<int, int>
     */
    public function tresholds(): array
    {
        $baseTresholds = [0, 0, 0, 0];
        foreach ($this->values as $characterLevel) {
            $characterTresholds = self::TRESHOLDS[$characterLevel];
            $baseTresholds[0] += $characterTresholds[0];
            $baseTresholds[1] += $characterTresholds[1];
            $baseTresholds[2] += $characterTresholds[2];
            $baseTresholds[3] += $characterTresholds[3];
        }
        return $baseTresholds;
    }
}
