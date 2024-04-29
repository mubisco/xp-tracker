<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Level;

final class MonstersXp
{
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

    public function totalXp(): int
    {
        $multiplier = $this->monsterMultiplier();
        $total = array_sum($this->values);
        return (int) floor($total * $multiplier);
    }

    private function monsterMultiplier(): float
    {
        $monsterQuantity = count($this->values);
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
}
