<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Domain\Experience;

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
    public function testItShouldReturnProperLevel(int $xp, int $expectedLevel): void
    {
        $sut = Experience::fromInt($xp);
        $this->assertEquals($xp, $sut->points());
        $this->assertEquals($expectedLevel, $sut->level());
    }
    /**
     * @return array<int,array<int,int>>
     */
    public function experienceAndLevelsData(): array
    {
        return [
            [0, 1],
            [299, 1],
            [300, 2],
            [899, 2],
            [900, 3],
            [2699, 3],
            [2700, 4],
            [6499, 4],
            [6500, 5],
            [13999, 5],
            [14000, 6],
            [22999, 6],
            [23000, 7],
            [33999, 7],
            [34000, 8],
            [47999, 8],
            [48000, 9],
            [63999, 9],
            [64000, 10],
            [84999, 10],
            [85000, 11],
            [99999, 11],
            [100000, 12],
            [119999, 12],
            [120000, 13],
            [139999, 13],
            [140000, 14],
            [164999, 14],
            [165000, 15],
            [194999, 15],
            [195000, 16],
            [224999, 16],
            [225000, 17],
            [264999, 17],
            [265000, 18],
            [304999, 18],
            [305000, 19],
            [354999, 19],
            [355000, 20]
        ];
    }
}
