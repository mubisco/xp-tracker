<?php

namespace XpTracker\Tests\Unit\Character\Application\Query;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Query\FindAllPartiesQuery;
use XpTracker\Character\Application\Query\FindAllPartiesQueryHandler;

class FindAllPartiesQueryHandlerTest extends TestCase
{
    private Connection&MockObject $connection;
    private FindAllPartiesQuery $query;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->query = new FindAllPartiesQuery();
    }

    /** @test */
    public function itShouldReturnEmptyResultsIfPartyHasNoCharacters(): void
    {
        $sut = new FindAllPartiesQueryHandler($this->connection);
        $result = ($sut)($this->query);
        $this->assertEmpty($result);
    }

    /** @test */
    public function itShouldReturnValues(): void
    {
        $receivedQueryValues = [
            [
                'partyUlid' => '01HWYXNCSGRNRFCVPVZSADT8V8',
                'partyName' => 'Cuquis',
                'partyCharacters' => 2
            ],
            [
                'partyUlid' => '01HWYXYTHR16JPEF8P2MBH1MV0',
                'partyName' => 'Chachis',
                'partyCharacters' => 1
            ],
        ];
        $this->connection->method('fetchAllAssociative')->willReturn($receivedQueryValues);
        $sut = new FindAllPartiesQueryHandler($this->connection);
        $result = ($sut)($this->query);
        $this->assertCount(2, $result);
        $this->assertEquals($receivedQueryValues, $result);
    }
}
