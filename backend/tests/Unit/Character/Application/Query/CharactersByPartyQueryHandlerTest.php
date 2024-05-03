<?php

namespace XpTracker\Tests\Unit\Character\Application\Query;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Query\CharactersByPartyQuery;
use XpTracker\Character\Application\Query\CharactersByPartyQueryHandler;
use XpTracker\Encounter\Domain\Party\WrongEncounterPartyUlidException;

class CharactersByPartyQueryHandlerTest extends TestCase
{
    private Connection&MockObject $connection;
    private CharactersByPartyQuery $query;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->query = new CharactersByPartyQuery(partyUlid: '01HWYXENYGYYT93NKZEK5GVMG5');
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterPartyUlidException::class);
        $sut = new CharactersByPartyQueryHandler($this->connection);
        $query = new CharactersByPartyQuery('asd');
        ($sut)($query);
    }

    /** @test */
    public function itShouldReturnEmptyResultsIfPartyHasNoCharacters(): void
    {
        $sut = new CharactersByPartyQueryHandler($this->connection);
        $result = ($sut)($this->query);
        $this->assertEmpty($result);
    }

    /** @test */
    public function itShouldReturnValues(): void
    {
        $receivedQueryValues = [
            [
                'character_id' => '01HWYXNCSGRNRFCVPVZSADT8V8',
                'character_data' => '{"name":"Chindas","xp":350,"next":900,"level":2}'
            ],
            [
                'character_id' => '01HWYXN9VR70RMWNFJE9RYH57D',
                'character_data' => '{"name":"Gustavo","xp":100,"next":300,"level":1}'
            ],
        ];
        $this->connection->method('fetchAllAssociative')->willReturn($receivedQueryValues);
        $sut = new CharactersByPartyQueryHandler($this->connection);
        $result = ($sut)($this->query);
        $this->assertCount(2, $result);
        $characterOne = [
            'ulid' => '01HWYXNCSGRNRFCVPVZSADT8V8',
            'name' => 'Chindas',
            'xp' => 350,
            'next' => 900,
            'level' => 2
        ];
        $characterTwo = [
            'ulid' => '01HWYXN9VR70RMWNFJE9RYH57D',
            'name' => 'Gustavo',
            'xp' => 100,
            'next' => 300,
            'level' => 1
        ];
        $this->assertEquals($characterOne, $result[0]);
        $this->assertEquals($characterTwo, $result[1]);
    }
}
