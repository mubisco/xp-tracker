<?php

namespace XpTracker\Tests\Unit\Encounter\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Encounter\Application\Query\EncountersByPartyUlidQueryHandler;
use XpTracker\Encounter\Domain\Party\WrongEncounterPartyUlidException;
use XpTracker\Encounter\Infrastructure\Entrypoint\Api\GetEncountersOfPartyController;

class GetEncountersOfPartyControllerTest extends TestCase
{
    private GetEncountersOfPartyController $sut;
    private EncountersByPartyUlidQueryHandler&Stub $useCase;

    protected function setUp(): void
    {
        $this->useCase = $this->createStub(EncountersByPartyUlidQueryHandler::class);
        $this->sut = new GetEncountersOfPartyController($this->useCase);
    }
    /** @test */
    public function itShouldReturnProperCodeWhenNoPartyFound(): void
    {
        $this->useCase->method('__invoke')->willThrowException(new PartyNotFoundException());
        $result = ($this->sut)('01HTA0HVG8G0WEQ48C2A2AE4BD');
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $result->getStatusCode());
    }

    /** @test */
    public function itShouldReturnProperCodeWhenWrongUlidProvided(): void
    {
        $this->useCase->method('__invoke')->willThrowException(new WrongEncounterPartyUlidException());
        $result = ($this->sut)('01HTA0HVG8G0WEQ48C2A2AE4BD');
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
    }

    /** @test */
    public function itShouldReturnProperResponseWhenPartyExists(): void
    {
        $expectedValues = ['partyId' => '', 'name' => 'Comando G', 'characters' => []];
        $this->useCase->method('__invoke')->willReturn($expectedValues);
        $result = ($this->sut)('01HTA0HVG8G0WEQ48C2A2AE4BD');
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());
        $this->assertEquals(json_encode($expectedValues), $result->getContent());
    }
}
