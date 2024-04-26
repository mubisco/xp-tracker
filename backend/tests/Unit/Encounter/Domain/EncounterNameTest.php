<?php

namespace XpTracker\Tests\Unit\Encounter\Domain;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\EncounterName;
use XpTracker\Encounter\Domain\WrongEncounterNameException;

class EncounterNameTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldThrowExceptionWhenWrongName(): void
    {
        $this->expectException(WrongEncounterNameException::class);
        EncounterName::fromString('Test;DROP TABLE users');
    }

    /**
     * @test
     * @dataProvider validNamesDataProvider
     */
    public function itShouldCreateItemFromNamedConstructor(string $name): void
    {
        $sut = EncounterName::fromString($name);
        $this->assertEquals($name, $sut->value());
    }
    /**
     * @return array<int,array<int,string>>
     */
    public function validNamesDataProvider(): array
    {
        return [
            ['Encounter Test'],
            ['Domain Encounter Test'],
            ['Test'],
        ];
    }
}
