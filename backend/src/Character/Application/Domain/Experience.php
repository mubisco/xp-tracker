<?php

declare(strict_types=1);

namespace XpTracker\Character\Application\Domain;

use InvalidArgumentException;

final class Experience
{
    private const XP_TRESHOLDS = [
        300,
        900,
        2700,
        6500,
        14000,
        23000,
        34000,
        48000,
        64000,
        85000,
        100000,
        120000,
        140000,
        165000,
        195000,
        225000,
        265000,
        305000,
        355000
    ];

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function __construct(private readonly int $points)
    {
        if ($points < 0) {
            throw new InvalidArgumentException('Experience points should not be below zero');
        }
    }

    public function points(): int
    {
        return $this->points;
    }

    public function level(): int
    {
        foreach (self::XP_TRESHOLDS as $index => $treshold) {
            if ($this->points < $treshold) {
                return $index + 1;
            }
        }
        return 20;
    }
}
