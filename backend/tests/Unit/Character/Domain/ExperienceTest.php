<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\Experience;

class ExperienceTest extends TestCase
{
    public function testItShouldBeOfProperClass(): void
    {
        $sut = Experience::fromInt(0);
        $this->assertInstanceOf(Experience::class, $sut);
    }

    public function testItShouldReturnProperExperiencePoints(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Experience::fromInt(-1);
    }

    /** @dataProvider experienceAndLevelsData */
    public function testItShouldReturnProperLevel(int $xp, int $expectedLevel, int $nextLevelTreshold): void
    {
        $sut = Experience::fromInt($xp);
        $this->assertEquals($xp, $sut->points());
        $this->assertEquals($expectedLevel, $sut->level());
        $this->assertEquals($nextLevelTreshold, $sut->nextLevel());
    }
    /**
     * @return array<int,array<int,int>>
     */
    public function experienceAndLevelsData(): array
    {
        return [
            [0, 1, 300],
            [299, 1, 300],
            [300, 2, 900],
            [899, 2, 900],
            [900, 3, 2700],
            [2699, 3, 2700],
            [2700, 4, 6500],
            [6499, 4, 6500],
            [6500, 5, 14000],
            [13999, 5, 14000],
            [14000, 6, 23000],
            [22999, 6, 23000],
            [23000, 7, 34000],
            [33999, 7, 34000],
            [34000, 8, 48000],
            [47999, 8, 48000],
            [48000, 9, 64000],
            [63999, 9, 64000],
            [64000, 10, 85000],
            [84999, 10, 85000],
            [85000, 11, 100000],
            [99999, 11, 100000],
            [100000, 12, 120000],
            [119999, 12, 120000],
            [120000, 13, 140000],
            [139999, 13, 140000],
            [140000, 14, 165000],
            [164999, 14, 165000],
            [165000, 15, 195000],
            [194999, 15, 195000],
            [195000, 16, 225000],
            [224999, 16, 225000],
            [225000, 17, 265000],
            [264999, 17, 265000],
            [265000, 18, 305000],
            [304999, 18, 305000],
            [305000, 19, 355000],
            [354999, 19, 355000],
            [355000, 20, 355000]
        ];
    }

    public function testShouldAddOtherExperience(): void
    {
        $sut = Experience::fromInt(800);
        $another = Experience::fromInt(101);
        $result = $sut->add($another);
        $this->assertInstanceOf(Experience::class, $result);
        $this->assertNotSame($sut, $result);
        $this->assertEquals(901, $result->points());
    }
}
