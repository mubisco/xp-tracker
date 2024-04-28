<?php

namespace XpTracker\Tests\Unit\Encounter\Domain\Monster;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\WrongMonsterValueException;

class EncounterMonsterTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenEmptyName(): void
    {
        $this->expectException(WrongMonsterValueException::class);
        EncounterMonster::fromStringValues('', '0');
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongChallengeRating(): void
    {
        $this->expectException(WrongMonsterValueException::class);
        EncounterMonster::fromStringValues('asd', '-1');
    }

    /** @test */
    public function itShouldReturnProperValues(): void
    {
        $sut = EncounterMonster::fromStringValues('Orc', '1');
        $this->assertEquals('Orc', $sut->name());
        $this->assertEquals('1', $sut->challengeRating());
        $this->assertEquals(200, $sut->xp());
    }
}
