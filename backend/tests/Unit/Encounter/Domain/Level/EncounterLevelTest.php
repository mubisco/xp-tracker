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
        $this->assertEquals(LevelTag::UNASSIGNED, $sut->level());
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
        LevelTag $expectedResult,
        int $monstersCrValue
    ): void {
        $sut = EncounterLevel::fromValues($levels, $monstersXp);
        $this->assertEquals($expectedResult->value, $sut->level()->value);
        $this->assertEquals($monstersCrValue, $sut->monstersChallengeRatingPoints());
    }
    /**
     * @return array<int,array<int,mixed>>
     */
    public function levelDataProvider(): array
    {
        return [
            [[], [], LevelTag::UNASSIGNED, 0],
            [[1], [], LevelTag::EMPTY, 0],
            [[1], [25], LevelTag::EASY, 25],
            [[1], [50], LevelTag::MEDIUM, 50],
            [[1], [75], LevelTag::HARD, 75],
            [[1], [100], LevelTag::DEADLY, 100],
            [[1, 2], [75], LevelTag::EASY, 75],
            [[1, 1], [100], LevelTag::MEDIUM, 100],
            [[1], [25, 25], LevelTag::HARD, 75],
            [[1, 2], [100], LevelTag::EASY, 100],
            [[1, 2], [25, 25, 25], LevelTag::MEDIUM, 150]
        ];
    }
}
