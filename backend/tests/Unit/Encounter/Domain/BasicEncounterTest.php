<?php

namespace XpTracker\Tests\Unit\Encounter\Domain;

use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Domain\BasicEncounter;
use XpTracker\Encounter\Domain\EncounterWasCreated;
use XpTracker\Encounter\Domain\Monster\EncounterMonster;
use XpTracker\Encounter\Domain\Monster\MonsterWasAdded;
use XpTracker\Encounter\Domain\Monster\MonsterWasRemoved;
use XpTracker\Encounter\Domain\Party\EncounterParty;
use XpTracker\Encounter\Domain\Party\PartyWasAssigned;
use XpTracker\Encounter\Domain\WrongEncounterNameException;
use XpTracker\Encounter\Domain\WrongEncounterUlidException;
use XpTracker\Shared\Domain\Event\DomainEvent;
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
            'status' => 'UNASSIGNED',
            'name' => 'Test',
            'party' => '',
            'monsters' => []
        ];
        $this->assertEquals($expectedValues, $sut->collect());
        $this->checkSingleEvent($sut, EncounterWasCreated::class);
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
        $this->checkSingleEvent($sut, MonsterWasAdded::class);
    }

    /** @test */
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
        $this->checkSingleEvent($sut, MonsterWasRemoved::class);
    }

    /** @test */
    public function itShouldApplyPartyProperly(): void
    {
        $sut = BasicEncounterOM::aBuilder()->build();
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $sut->assignToParty($party);
        $data = $sut->collect();
        $this->assertEquals('01HWTEG560RMHQ52KC5SGGPCEJ', $data['party']);
        $this->assertEquals('EMPTY', $data['status']);
        $this->checkSingleEvent($sut, PartyWasAssigned::class);
    }

    /** @test */
    public function itShouldApplyPartyProperlyWhenMonstersAdded(): void
    {
        $orc = EncounterMonster::fromStringValues(name: 'Orc', challengeRating: '1/2');
        $kobold = EncounterMonster::fromStringValues(name: 'Kobold', challengeRating: '1/2');
        $sut = BasicEncounterOM::aBuilder()->withMonster($orc)->withMonster($kobold)->build();
        $party = new EncounterParty('01HWTEG560RMHQ52KC5SGGPCEJ', [1,2]);
        $sut->assignToParty($party);
        $data = $sut->collect();
        $this->assertEquals('DEADLY', $data['status']);
    }

    private function checkSingleEvent(BasicEncounter $sut, string $expectedEventClass): void
    {
        $events = $sut->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf($expectedEventClass, $events[0]);
        $this->assertThat($events[0]->occurredOn(), $this->assertion);
    }
}
