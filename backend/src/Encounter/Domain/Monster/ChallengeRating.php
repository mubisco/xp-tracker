<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Domain\Monster;

final class ChallengeRating
{
    private const ALLOWED_VALUES = [
        '0' => 10,
        '1/8' => 25,
        '1/4' => 50,
        '1/2' => 100,
        '1' => 200,
        '2' => 450,
        '3' => 700,
        '4' => 1_100,
        '5' => 1_800,
        '6' => 2_300,
        '7' => 2_900,
        '8' => 3_900,
        '9' => 5_000,
        '10' => 5_900,
        '11' => 7_200,
        '12' => 8_400,
        '13' => 10_000,
        '14'  => 11_500,
        '15'  => 13_000,
        '16'  => 15_000,
        '17'  => 18_000,
        '18'  => 20_000,
        '19'  => 22_000,
        '20'  => 25_000,
        '21'  => 33_000,
        '22'  => 41_000,
        '23'  => 50_000,
        '24'  => 62_000,
        '25'  => 75_000,
        '26'  => 90_000,
        '30'  => 155_000
    ];

    public function __construct(private readonly string $value)
    {
        $keys = array_keys(self::ALLOWED_VALUES);
        if (!in_array($value, $keys)) {
            throw new InvalidChallengeRatingValueException(
                "Provided value of {$value} is not valid for ChallengeRating"
            );
        }
    }

    public function rating(): string
    {
        return $this->value;
    }

    public function xp(): int
    {
        return self::ALLOWED_VALUES[$this->value];
    }
}
