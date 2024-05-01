<?php

namespace XpTracker\Tests\Unit\Encounter\Domain\Level;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\Level\MonstersXp;

class MonsterXpTest extends TestCase
{
    /**
     * @test
     * @dataProvider monsterXpValues
     * * @param array<int,mixed> $xpValues
     */
    public function itShouldReturnProperValues(array $xpValues, int $expectedValue): void
    {
        $sut = MonstersXp::fromIntValues($xpValues);
        $this->assertEquals($expectedValue, $sut->totalXp());
    }
    /**
     * @return array<int,array<int,mixed>>
     */
    public function monsterXpValues(): array
    {
        return [
            [[100, 100, 100, 100, 100, 100, 100], 1750],
            [[100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100], 3300],
            [[10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10], 600]
        ];
    }
}
