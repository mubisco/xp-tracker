<?php

namespace XpTracker\Tests\Unit\Encounter\Application\Query;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XpTracker\Encounter\Application\Query\EncountersByPartyUlidQuery;
use XpTracker\Encounter\Application\Query\EncountersByPartyUlidQueryHandler;
use XpTracker\Encounter\Domain\Party\WrongEncounterPartyUlidException;

class EncountersByPartyUlidQueryHandlerTest extends TestCase
{
    private Connection&MockObject $connection;
    private EncountersByPartyUlidQuery $query;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->query = new EncountersByPartyUlidQuery('01HX1F2C5GYR1QENC68HXVRFCG');
    }

    /** @test */
    public function itShouldThrowExceptionWhenWrongUlid(): void
    {
        $this->expectException(WrongEncounterPartyUlidException::class);
        $query = new EncountersByPartyUlidQuery('asd');
        $sut = new EncountersByPartyUlidQueryHandler($this->connection);
        ($sut)($query);
    }

    /** @test */
    public function itShouldReturnEmptyResultsIfPartyHasNoCharacters(): void
    {
        $sut = new EncountersByPartyUlidQueryHandler($this->connection);
        $result = ($sut)($this->query);
        $this->assertEmpty($result);
    }

    /** @test */
    public function itShouldReturnValues(): void
    {
        $receivedQueryValues = $this->getRawQueryValues();
        $this->connection->method('fetchAllAssociative')->willReturn($receivedQueryValues);
        $sut = new EncountersByPartyUlidQueryHandler($this->connection);
        $result = ($sut)($this->query);
        $this->assertCount(2, $result);
        $expectedResult = [
            'ulid' => '01HWZRW77T1HY30VTD2P4DHSVB',
            'name' => 'Test 2',
            'party' => '01HWZRT7TPHEMNSBFJDRVXBMSV',
            'status' => 'OPEN',
            'level' => 'EMPTY',
            'totalXp' => 0,
            'totalCrPoints' => 0,
            'monsters' => []
        ];
        $this->assertEquals($expectedResult, $result[1]);
    }
    /**
     * @return array<int,array<string,string>>
     */
    private function getRawQueryValues(): array
    {
        return [
            [
                'encounter_id' =>  '01HWZRV66E4QASXJDW52GSSTXS',
                'party_id' =>  '01HX1F2C5GYR1QENC68HXVRFCG',
                'status' =>  'RESOLVED',
                'data' => '{"name":"Test One","party":"01HWZRT7TPHEMNSBFJDRVXBMSV","status":"RESOLVED","level":"DEADLY","totalXp":2325,"totalCrPoints":3487,"monsters":[{"name":"Kobold","challengeRating":"1\/8","experiencePoints":25},{"name":"Bicho\u00f1o","challengeRating":"6","experiencePoints":2300}]}'
            ], [
                'encounter_id' => '01HWZRW77T1HY30VTD2P4DHSVB',
                'party_id' => '01HX1F2C5GYR1QENC68HXVRFCG',
                'status' => 'OPEN',
                'data' =>  '{"name":"Test 2","party":"01HWZRT7TPHEMNSBFJDRVXBMSV","status":"OPEN","level":"EMPTY","totalXp":0,"totalCrPoints":0,"monsters":[]}'
            ]

        ];
    }
}
