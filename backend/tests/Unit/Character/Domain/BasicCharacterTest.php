<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use DomainException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Domain\BasicCharacter;
use XpTracker\Character\Application\Domain\CharacterWasCreated;

class BasicCharacterTest extends TestCase
{
    public function testItShouldReturnProperUlid(): void
    {
        $sut = BasicCharacter::create('01HP9BFM98404KRE15AKWG6YBB');
        $this->assertEquals('01HP9BFM98404KRE15AKWG6YBB', $sut->id());
    }

    public function testItShouldThrowErrorWhenEventAggregateIdDoesNotMatch(): void
    {
        $this->expectException(DomainException::class);
        $sut = BasicCharacter::create('01HP9BFM98404KRE15AKWG6YBB');
        $createEvent = new CharacterWasCreated(
            '01HP9CTGQ4MD6Z4QSJWPNCRZHS',
            'Chindasvinto',
            'pousa',
            0,
            30
        );
        $sut->applyEvent($createEvent);
    }

    public function testItShouldApplyCreatedEventProperly(): void
    {
        $sut = BasicCharacter::create('01HP9BFM98404KRE15AKWG6YBB');
        $createEvent = new CharacterWasCreated(
            '01HP9BFM98404KRE15AKWG6YBB',
            'Chindasvinto',
            'pousa',
            0,
            30
        );
        $sut->applyEvent($createEvent);
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertSame($events[0], $createEvent);
    }
}
