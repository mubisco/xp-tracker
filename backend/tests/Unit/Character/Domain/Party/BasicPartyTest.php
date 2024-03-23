<?php

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Character\Domain\Party\CharacterWasAdded;
use XpTracker\Character\Domain\Party\PartyWasCreated;
use XpTracker\Shared\Domain\Event\EventCollection;

class BasicPartyTest extends TestCase
{
    public function testItShouldThrowExceptionWhenWrongId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        BasicParty::create('1HPWQC2NFH8Y8Z1HG6BARZGNH', 'Comando G');
    }

    public function testItShouldReturnProperData(): void
    {
        $sut = BasicParty::create('01HPWV7N1NEJK77X40JZCHBRMH', 'Equipo A');
        $this->assertEquals('01HPWV7N1NEJK77X40JZCHBRMH', $sut->id());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(PartyWasCreated::class, $event);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->occurredOn());
        $this->assertEquals('01HPWV7N1NEJK77X40JZCHBRMH', $event->id());
        $this->assertEquals('Equipo A', $event->partyName);
        $expectedJson = '{"name":"Equipo A","characters":[]}';
        $this->assertEquals($expectedJson, $sut->toJson());
    }

    public function testItShouldAddCharacterProperly(): void
    {
        $now = new DateTimeImmutable();
        $sut = BasicParty::create('01HPWV7N1NEJK77X40JZCHBRMH', 'Equipo A');
        $character = BasicCharacter::create('01HSNWFV002S03C6PGJ402NK4H', 'Darling', 300);
        $sut->add($character);
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $event = $events[1];
        $this->assertInstanceOf(CharacterWasAdded::class, $event);
        $eventDate = $event->occurredOn();
        $delta = abs($eventDate->getTimestamp() - $now->getTimestamp());
        $this->assertTrue($delta < 5);
        $this->assertEquals($event->addedCharacterId, $character->id());
        $this->assertEquals(1, $sut->characterCount());
        $expectedJson = '{"name":"Equipo A","characters":["' . $character->id() . '"]}';
        $this->assertEquals($expectedJson, $sut->toJson());
    }

    public function testItShouldRehidrateProperlyFromEvents(): void
    {
        $events = [
            new PartyWasCreated('01HSNY23CRKKWFJ9H78AFK9C49', 'Comando G'),
            new CharacterWasAdded('01HSNY23CRKKWFJ9H78AFK9C49', '01HSNY2NYGJPAC499JEMY7BXGV')
        ];
        $eventCollection = EventCollection::fromValues('01HSNY23CRKKWFJ9H78AFK9C49', $events);
        $sut = BasicParty::fromEvents($eventCollection);
        $this->assertEquals('01HSNY23CRKKWFJ9H78AFK9C49', $sut->id());
        $expectedJson = '{"name":"Comando G","characters":["01HSNY2NYGJPAC499JEMY7BXGV"]}';
        $this->assertEquals($expectedJson, $sut->toJson());
    }
}
