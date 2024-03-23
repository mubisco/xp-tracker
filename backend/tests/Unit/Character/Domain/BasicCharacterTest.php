<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use DateTimeImmutable;
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

class BasicCharacterTest extends TestCase
{
    private SharedUlid $randomUlid;

    protected function setUp(): void
    {
        $this->randomUlid = SharedUlid::fromEmpty();
    }

    public function testItShouldCreateCharacter(): void
    {
        $sut = BasicCharacter::create($this->randomUlid->ulid(), 'Darling', 300);
        $this->assertEquals($this->randomUlid->ulid(), $sut->id());
        $expectedValues = '{"name":"Darling","xp":300,"level":2,"next":900}';
        $this->assertEquals($expectedValues, $sut->toJson());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterWasCreated::class, $event);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->occurredOn());
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
        $expectedValues = '{"name":"Chindas","xp":901,"level":3,"next":2700}';
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
        $event = new CharacterWasCreated(id: $this->randomUlid->ulid(), name: 'Chindas', experiencePoints: 800);
        $eventCollection = EventCollection::fromValues($this->randomUlid->ulid(), [$event]);
        $sut = BasicCharacter::fromEvents($eventCollection);
        $sut->addExperience(Experience::fromInt(99));
        $expectedValues = '{"name":"Chindas","xp":899,"level":2,"next":900}';
        $this->assertEquals($expectedValues, $sut->toJson());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ExperienceWasUpdated::class, $events[0]);
    }

    public function testItShouldCreateLevelEventWhenLevelIncreased(): void
    {
        $events = [
            new CharacterWasCreated(id: $this->randomUlid->ulid(), name: 'Chindas', experiencePoints: 800),
            new ExperienceWasUpdated(id: $this->randomUlid->ulid(), points: 99)
        ];
        $eventCollection = EventCollection::fromValues($this->randomUlid->ulid(), $events);
        $sut = BasicCharacter::fromEvents($eventCollection);
        $sut->addExperience(Experience::fromInt(2));
        $events = $sut->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(ExperienceWasUpdated::class, $events[0]);
        $this->assertInstanceOf(DateTimeImmutable::class, $events[0]->occurredOn());
        $this->assertInstanceOf(LevelWasIncreased::class, $events[1]);
        $this->assertInstanceOf(DateTimeImmutable::class, $events[1]->occurredOn());
        $expectedValues = '{"name":"Chindas","xp":901,"level":3,"next":2700}';
        $this->assertEquals($expectedValues, $sut->toJson());
    }

    public function testItShouldJoinToParty(): void
    {
        $events = [
            new CharacterWasCreated(id: $this->randomUlid->ulid(), name: 'Chindas', experiencePoints: 800),
            new ExperienceWasUpdated(id: $this->randomUlid->ulid(), points: 99)
        ];
        $eventCollection = EventCollection::fromValues($this->randomUlid->ulid(), $events);
        $sut = BasicCharacter::fromEvents($eventCollection);
        $party = BasicParty::create('01HSPCYKE8HAA363XY0KWBGDNY', 'Comando G');
        $sut->join($party);
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterJoined::class, $event);
        $now = new DateTimeImmutable();
        $eventDate = $event->occurredOn();
        $delta = abs($eventDate->getTimestamp() - $now->getTimestamp());
        $this->assertTrue($delta < 5);
        $this->assertEquals($event->partyId, $party->id());
    }

    public function testItShouldThrowExceptionWhenCharacterAlreadyInParty(): void
    {
        $this->expectException(CharacterAlreadyInPartyException::class);
        $events = [
            new CharacterWasCreated(id: $this->randomUlid->ulid(), name: 'Chindas', experiencePoints: 800),
            new ExperienceWasUpdated(id: $this->randomUlid->ulid(), points: 99),
            new CharacterJoined(characterId: $this->randomUlid->ulid(), partyId: '01HSPDCXEGPMSPHQGGTV1QZEAZ')
        ];
        $eventCollection = EventCollection::fromValues($this->randomUlid->ulid(), $events);
        $sut = BasicCharacter::fromEvents($eventCollection);
        $party = BasicParty::create('01HSPCYKE8HAA363XY0KWBGDNY', 'Comando G');
        $sut->join($party);
    }
}
