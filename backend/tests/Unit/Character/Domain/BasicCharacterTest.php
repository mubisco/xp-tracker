<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Domain\BasicCharacter;
use XpTracker\Character\Domain\CharacterWasCreated;
use XpTracker\Shared\Domain\Identity\SharedUlid;

class BasicCharacterTest extends TestCase
{
    // public function testItShouldThrowErrorWhenEventAggregateIdDoesNotMatch(): void
    // {
    //     $this->expectException(DomainException::class);
    //     $sut = BasicCharacter::create('01HP9BFM98404KRE15AKWG6YBB');
    //     $createEvent = new CharacterWasCreated(
    //         '01HP9CTGQ4MD6Z4QSJWPNCRZHS',
    //         'Chindasvinto',
    //         'pousa',
    //         0,
    //         30
    //     );
    //     $sut->applyEvent($createEvent);
    // }

    public function testItShouldApplyCreatedEventProperly(): void
    {
        $sut = BasicCharacter::create(
            '01HP9BFM98404KRE15AKWG6YBB',
            'Darling',
            300
        );
        $this->assertEquals('01HP9BFM98404KRE15AKWG6YBB', $sut->id());
        $expectedValues = '{"name":"Darling","xp":300,"level":2,"next":900}';
        $this->assertEquals($expectedValues, $sut->toJson());
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CharacterWasCreated::class, $event);
        $this->assertEquals('01HP9BFM98404KRE15AKWG6YBB', $event->id);
        $this->assertEquals('Darling', $event->name);
        $this->assertEquals(300, $event->experiencePoints);
    }

    /** @dataProvider wrongCreationData */
    public function testItShouldThrowErrorWhenWrongData(string $ulid, string $name, int $xp): void
    {
        $this->expectException(InvalidArgumentException::class);
        BasicCharacter::create($ulid, $name, $xp);
    }
    /**
     * @return array<int,array<int,mixed>>
     */
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
        $ulid = SharedUlid::fromEmpty();
        $event = new CharacterWasCreated(id: $ulid->ulid(), name: 'Chindas', experiencePoints: 901);
        $sut = BasicCharacter::fromEvents($ulid, [$event]);
        $this->assertInstanceOf(BasicCharacter::class, $sut);
        $this->assertEquals($ulid->ulid(), $sut->id());
        $expectedValues = '{"name":"Chindas","xp":901,"level":3,"next":2700}';
        $this->assertEquals($expectedValues, $sut->toJson());
    }
}
