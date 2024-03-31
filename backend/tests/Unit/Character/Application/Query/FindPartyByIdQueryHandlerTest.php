<?php

namespace XpTracker\Tests\Unit\Character\Application\Query;

use PHPUnit\Framework\TestCase;
use XpTracker\Character\Application\Query\FindPartyByIdQuery;
use XpTracker\Character\Application\Query\FindPartyByIdQueryHandler;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Tests\Unit\Character\Domain\Party\FullPartyReadModelStub;
use XpTracker\Tests\Unit\Character\Domain\Party\NotFoundFullPartyReadModelStub;

class FindPartyByIdQueryHandlerTest extends TestCase
{
    /** @test */
    public function itShouldThrowExceptionWhenWrongPartyUlid(): void
    {
        $this->expectException(InvalidPartyUlidValueException::class);
        $sut = new FindPartyByIdQueryHandler(new NotFoundFullPartyReadModelStub());
        $query = new FindPartyByIdQuery('asd');
        ($sut)($query);
    }

    /** @test */
    public function itShouldThrowExceptionWhenNoPartyFound(): void
    {
        $this->expectException(PartyNotFoundException::class);
        $sut = new FindPartyByIdQueryHandler(new NotFoundFullPartyReadModelStub());
        $query = new FindPartyByIdQuery('01HTA1DYXGPA6P56E157ZRQBEQ');
        ($sut)($query);
    }

    /** @test */
    public function itShouldReturnProperData(): void
    {
        $sut = new FindPartyByIdQueryHandler(new FullPartyReadModelStub());
        $query = new FindPartyByIdQuery('01HTA1DYXGPA6P56E157ZRQBEQ');
        $result = ($sut)($query);
        $this->assertEquals('01HTA1DYXGPA6P56E157ZRQBEQ', $result['partyId']);
    }
}
