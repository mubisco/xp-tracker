<?php

namespace XpTracker\Tests\Unit\Character\Domain\Party;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\CharacterAlreadyInPartyException;
use XpTracker\Character\Domain\CharacterJoined;
use XpTracker\Character\Domain\CharacterWasCreated;
use XpTracker\Character\Domain\ExperienceWasUpdated;
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

    /** @group asd */
    public function testItShouldAddCharacterProperly(): void
    {
        $now = new DateTimeImmutable();
        $sut = BasicParty::create('01HPWV7N1NEJK77X40JZCHBRMH', 'Equipo A');
        $events = [
            new CharacterWasCreated(id: '01HSPDYNT0GPS8Q7GMRB8XVPWX', name: 'Chindas', experiencePoints: 800),
            new ExperienceWasUpdated(id: '01HSPDYNT0GPS8Q7GMRB8XVPWX', points: 99)
        ];
        $eventCollection = EventCollection::fromValues('01HSPDYNT0GPS8Q7GMRB8XVPWX', $events);
        $character = BasicCharacter::fromEvents($eventCollection);
        $sut->add($character);
        $events = $sut->pullEvents();
        $this->assertCount(3, $events);
        $event = $events[1];
        $this->assertInstanceOf(CharacterWasAdded::class, $event);
        $characterEvent = $events[2];
        $this->assertInstanceOf(CharacterJoined::class, $characterEvent);
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

    public function testItShouldThrowExceptionAddCharacterInAnotherParty(): void
    {
        $this->expectException(CharacterAlreadyInPartyException::class);
        $events = [
            new PartyWasCreated('01HSNY23CRKKWFJ9H78AFK9C49', 'Comando G'),
            new CharacterWasAdded('01HSNY23CRKKWFJ9H78AFK9C49', '01HSNY2NYGJPAC499JEMY7BXGV')
        ];
        $eventCollection = EventCollection::fromValues('01HSNY23CRKKWFJ9H78AFK9C49', $events);
        $sut = BasicParty::fromEvents($eventCollection);
        $events = [
            new CharacterWasCreated(id: '01HSPDYNT0GPS8Q7GMRB8XVPWX', name: 'Chindas', experiencePoints: 800),
            new ExperienceWasUpdated(id: '01HSPDYNT0GPS8Q7GMRB8XVPWX', points: 99),
            new CharacterJoined(characterId: '01HSPDYNT0GPS8Q7GMRB8XVPWX', partyId: '01HSPDCXEGPMSPHQGGTV1QZEAZ')
        ];
        $eventCollection = EventCollection::fromValues('01HSPDYNT0GPS8Q7GMRB8XVPWX', $events);
        $character = BasicCharacter::fromEvents($eventCollection);
        $sut->add($character);
    }
}
