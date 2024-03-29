<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\CharacterAlreadyInPartyException;
use XpTracker\Character\Domain\CharacterJoined;
use XpTracker\Character\Domain\CharacterWasCreated;
use XpTracker\Character\Domain\Experience;
use XpTracker\Character\Domain\ExperienceWasUpdated;
use XpTracker\Character\Domain\LevelWasIncreased;
use XpTracker\Character\Domain\Party\BasicParty;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Shared\Domain\Identity\SharedUlid;
use XpTracker\Tests\Unit\Character\Domain\Party\PartyOM;
use XpTracker\Tests\Unit\Custom\IsImmediateDate;

class BasicCharacterTest extends TestCase
{
    private SharedUlid $randomUlid;
    private IsImmediateDate $assertion;

    protected function setUp(): void
    {
        $this->randomUlid = SharedUlid::fromEmpty();
        $this->assertion = new IsImmediateDate();
    }

    public function testItShouldCreateCharacter(): void
    {
        $sut = BasicCharacter::create($this->randomUlid->ulid(), 'Darling', 300);
        $this->assertEquals($this->randomUlid->ulid(), $sut->id());
        $expectedValues = '{"name":"Darling","xp":300,"level":2,"next":900,"partyId":""}';
        $this->assertEquals($expectedValues, $sut->toJson());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterWasCreated::class, $event);
        $this->assertThat($event->occurredOn(), $this->assertion);
    }

    /** @dataProvider wrongCreationData */
    public function testItShouldThrowErrorWhenWrongData(string $ulid, string $name, int $xp): void
    {
        $this->expectException(InvalidArgumentException::class);
        BasicCharacter::create($ulid, $name, $xp);
    }
    /** @return array<int,array<int,mixed>> */
    public function wrongCreationData(): array
    {
        return [
            ['01HP9BFM98404KRE15AKWG6YB', 'Darling', 300],
            ['01HP9BFM98404KRE15AKWG6YBB', '', 300],
            ['01HP9BFM98404KRE15AKWG6YBB', 'Darling', -1]
        ];
    }

    public function testShouldHydrateCharacterFromEvents(): void
    {
        $events = [
            new CharacterWasCreated(id: $this->randomUlid->ulid(), name: 'Chindas', experiencePoints: 800),
            new ExperienceWasUpdated(id: $this->randomUlid->ulid(), points: 101),
            new LevelWasIncreased(id: $this->randomUlid->ulid(), currentLevel: 2),
        ];
        $eventCollection = EventCollection::fromValues($this->randomUlid->ulid(), $events);
        $sut = BasicCharacter::fromEvents($eventCollection);
        $this->assertInstanceOf(BasicCharacter::class, $sut);
        $this->assertEquals($this->randomUlid->ulid(), $sut->id());
        $expectedValues = '{"name":"Chindas","xp":901,"level":3,"next":2700,"partyId":""}';
        $this->assertEquals($expectedValues, $sut->toJson());
        $this->assertCount(0, $sut->pullEvents());
    }

    public function testShouldThrowErrorWhenEventsDoesNotMatchUlid(): void
    {
        $this->expectException(DomainException::class);
        $anotherUlid = SharedUlid::fromEmpty();
        $events = [
            new CharacterWasCreated(id: $this->randomUlid->ulid(), name: 'Chindas', experiencePoints: 901),
            new ExperienceWasUpdated(id: $anotherUlid->ulid(), points: 901)
        ];
        $eventCollection = EventCollection::fromValues($this->randomUlid->ulid(), $events);
        BasicCharacter::fromEvents($eventCollection);
    }

    public function testItShouldAddExperienceProperly(): void
    {
        $sut = CharacterOM::aBuilder()->withExperience(800)->build();
        $sut->addExperience(Experience::fromInt(99));
        $expectedValues = '{"name":"Chindas","xp":899,"level":2,"next":900,"partyId":""}';
        $this->assertEquals($expectedValues, $sut->toJson());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ExperienceWasUpdated::class, $events[0]);
    }

    public function testItShouldCreateLevelEventWhenLevelIncreased(): void
    {
        $sut = CharacterOM::aBuilder()->withExperience(899)->build();
        $sut->addExperience(Experience::fromInt(2));
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(ExperienceWasUpdated::class, $events[0]);
        $this->assertInstanceOf(LevelWasIncreased::class, $events[1]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
        $this->assertThat($events[1]->occurredOn(), $this->assertion);
        $expectedValues = '{"name":"Chindas","xp":901,"level":3,"next":2700,"partyId":""}';
        $this->assertEquals($expectedValues, $sut->toJson());
    }

    public function testItShouldJoinToParty(): void
    {
        $sut = CharacterOM::aBuilder()->withExperience(899)->build();
        $party = BasicParty::create('01HSPCYKE8HAA363XY0KWBGDNY', 'Comando G');
        $sut->join($party);
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterJoined::class, $event);
        $this->assertThat($event->occurredOn(), $this->assertion);
        $this->assertEquals($event->partyId, $party->id());
        $expectedValues = '{"name":"Chindas","xp":899,"level":2,"next":900,"partyId":"' . $party->id() . '"}';
        $this->assertEquals($expectedValues, $sut->toJson());
    }

    public function testItShouldThrowExceptionWhenCharacterAlreadyInParty(): void
    {
        $this->expectException(CharacterAlreadyInPartyException::class);
        $party = PartyOM::aBuilder()->build();
        $anotherParty = PartyOM::aBuilder()->build();
        $character = CharacterOM::aBuilder()->build();
        $character->join($party);
        $character->join($anotherParty);
    }
}
