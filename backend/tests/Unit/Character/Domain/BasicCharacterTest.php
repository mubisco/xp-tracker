<?php

namespace XpTracker\Tests\Unit\Character\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Domain\BasicCharacter;
use XpTracker\Character\Application\Domain\CharacterWasCreated;

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
}
