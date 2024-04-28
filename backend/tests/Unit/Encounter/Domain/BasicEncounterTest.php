<?php

namespace XpTracker\Tests\Unit\Encounter\Domain;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
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
        $expectedJsonValue = '{"name":"Test","monsters":[]}';
        $this->assertEquals($expectedJsonValue, $sut->toJson());
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
        $sut = BasicEncounter::create('01HTTPV1VG40BB2B9NX6KKJZ50', 'Test');
        $monster = EncounterMonster::fromStringValues('Orc', '1/2');
        $sut->addMonster($monster);
        $this->assertEquals(1, $sut->monsterQuantity());
        $anotherMonster = EncounterMonster::fromStringValues('Kobold', '1/2');
        $sut->addMonster($anotherMonster);
        $this->assertEquals(2, $sut->monsterQuantity());
        $this->assertCount(3, $sut->pullEvents());
    }

    /** @test */
    public function itShouldApplyMonstersFromEvents(): void
    {
        $ulid = '01HWJ7QMXG4Y77MC7DG6C5T0DQ';
        $createEvent = new EncounterWasCreated(id: $ulid, name: 'Test');
        $addMonsterEvent = new MonsterWasAdded(
            encounterUlid: $ulid,
            monsterName: 'Orc',
            challengeRating: '1/2'
        );
        $collection = EventCollection::fromValues($ulid, [$createEvent, $addMonsterEvent]);
        $sut = BasicEncounter::fromEvents($collection);
        $this->assertCount(0, $sut->pullEvents());
        $this->assertEquals(1, $sut->monsterQuantity());
        $expectedValues = [
            'name' => 'Test',
            'monsters' => [
                ['name' => 'Orc', 'challengeRating' => '1/2', 'experiencePoints' => 100]

            ]
        ];
        $this->assertEquals($expectedValues, $sut->collect());
    }
}
