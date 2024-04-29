<?php

namespace XpTracker\Tests\Unit\Encounter\Domain\Level;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\Level\EncounterLevel;
use XpTracker\Encounter\Domain\Level\LevelTag;

class EncounterLevelTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedFromEmpty(): void
    {
        $sut = EncounterLevel::empty();
        $this->assertEquals(LevelTag::UNNASSIGNED, $sut->level());
    }

    /**
     * @test
     */
    public function itShouldReturnEmptyWhenNoMonsters(): void
    {
        $sut = EncounterLevel::fromPartyLevels([1, 2]);
        $this->assertEquals(LevelTag::EMPTY, $sut->level());
    }

    /**
     * @test
     * @dataProvider levelDataProvider
     * @param array<int,int> $levels
     * @param array<int,int> $monstersXp
     */
    public function itShouldReturnProperLevel(
        array $levels,
        array $monstersXp,
        LevelTag $expectedResult
    ): void {
        $sut = EncounterLevel::fromValues($levels, $monstersXp);
        $this->assertEquals($expectedResult->value, $sut->level()->value);
    }
    /**
     * @return array<int,array<int,mixed>>
     */
    public function levelDataProvider(): array
    {
        return [
            [[], [], LevelTag::UNNASSIGNED],
            [[1], [], LevelTag::EMPTY],
            [[1], [25], LevelTag::EASY],
            [[1], [50], LevelTag::MEDIUM],
            [[1], [75], LevelTag::HARD],
            [[1], [100], LevelTag::DEADLY],
            [[1, 2], [75], LevelTag::EASY],
            [[1, 1], [100], LevelTag::MEDIUM],
            [[1], [25, 25], LevelTag::HARD],
            [[1, 2], [100], LevelTag::EASY],
            [[1, 2], [25, 25, 25], LevelTag::MEDIUM]
        ];
    }
}
