<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\CharacterName;

class CharacterNameTest extends TestCase
{
    public function testItShouldThrowExceptionWhenEmtpyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        CharacterName::fromString('');
    }

    public function testItShouldReturnProperValue(): void
    {
        $sut = CharacterName::fromString('Darling');
        $this->assertEquals('Darling', $sut->name());
    }
}
