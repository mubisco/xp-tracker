<?php

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XpTracker\Character\Application\Query\FindPartyByIdQueryHandler;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;
use XpTracker\Character\Infrastructure\Entrypoint\Api\GetPartyController;

class GetPartyControllerTest extends TestCase
{
    private GetPartyController $sut;
    private FindPartyByIdQueryHandler&Stub $useCase;

    protected function setUp(): void
    {
        $this->useCase = $this->createStub(FindPartyByIdQueryHandler::class);
        $this->sut = new GetPartyController($this->useCase);
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
        $this->useCase->method('__invoke')->willThrowException(new InvalidPartyUlidValueException());
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
