<?php

namespace XpTracker\Tests\Unit\Encounter\Domain;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
use XpTracker\Encounter\Domain\Monster\MonsterWasRemoved;
use XpTracker\Encounter\Domain\WrongEncounterNameException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Domain\Event\EventCollection;
use XpTracker\Tests\Unit\Custom\IsImmediateDate;

class BasicEncounterTest extends TestCase
{
    private IsImmediateDate $assertion;

    protected function setUp(): void
    {
        $this->assertion = new IsImmediateDate();
    }
    /** @test */
    public function itShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterUlidException::class);
        BasicEncounter::create('asd', 'Test Encounter');
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongName(): void
    {
        $this->expectException(WrongEncounterNameException::class);
        BasicEncounter::create('01HTTPV1VG40BB2B9NX6KKJZ50', 'Test;DROP TABLE users');
    }

    /** @test */
    public function itShouldCreateInstanceProperly(): void
    {
        $sut = BasicEncounter::create('01HTTPV1VG40BB2B9NX6KKJZ50', 'Test');
        $this->assertEquals('01HTTPV1VG40BB2B9NX6KKJZ50', $sut->id());
        $expectedValues = [
            'level' => 'UNASSIGNED',
            'name' => 'Test',
            'party' => '',
            'monsters' => []
        ];
        $this->assertEquals($expectedValues, $sut->collect());
    }

    /** @test */
    public function itShouldCreateProperEvents(): void
    {
        $sut = BasicEncounter::create('01HTTPV1VG40BB2B9NX6KKJZ50', 'Test');
        $this->assertCount(1, $sut->pullEvents());
        $event = $sut->pullEvents()[0];
        $this->assertInstanceOf(EncounterWasCreated::class, $event);
        $this->assertEquals('01HTTPV1VG40BB2B9NX6KKJZ50', $event->id());
        $this->assertEquals('Test', $event->name);
        $this->assertThat($event->occurredOn(), $this->assertion);
    }

    /** @test */
    public function itShouldAddMonsterProperly(): void
    {
        $sut = BasicEncounterOM::aBuilder()->build();
        $monster = EncounterMonster::fromStringValues('Orc', '1/2');
        $sut->addMonster($monster);
        $this->assertEquals(1, $sut->monsterQuantity());
        $this->assertCount(1, $sut->pullEvents());
        $expectedMonsters = [['name' => 'Orc', 'challengeRating' => '1/2', 'experiencePoints' => 100]];
        $data = $sut->collect();
        $this->assertEquals($expectedMonsters, $data['monsters']);
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MonsterWasAdded::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
    }

    /**
     * @test
     * it_should_remove_monster_properly
     */
    public function itShouldRemoveMonsterProperly(): void
    {
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->build();
        $sut->removeMonster($orc);
        $this->assertEquals(1, $sut->monsterQuantity());
        $data = $sut->collect();
        $this->assertEquals(
            [['name' => 'Kobold', 'challengeRating' => '1/2', 'experiencePoints' => 100]],
            $data['monsters']
        );
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MonsterWasRemoved::class, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
    }
}
