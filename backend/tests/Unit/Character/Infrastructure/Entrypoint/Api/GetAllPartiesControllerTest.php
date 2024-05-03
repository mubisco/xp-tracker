<?php

namespace XpTracker\Tests\Unit\Character\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XpTracker\Character\Application\Query\FindAllPartiesQueryHandler;
use XpTracker\Character\Infrastructure\Entrypoint\Api\GetAllPartiesController;

class GetAllPartiesControllerTest extends TestCase
{
    private GetAllPartiesController $sut;
    private FindAllPartiesQueryHandler&Stub $useCase;

    protected function setUp(): void
    {
        $this->useCase = $this->createStub(FindAllPartiesQueryHandler::class);
        $this->sut = new GetAllPartiesController($this->useCase);
    }

    /** @test */
    public function itShouldReturnProperResponseWhenPartyExists(): void
    {
        $expectedValues = ['partyId' => '', 'name' => 'Comando G', 'characters' => []];
        $this->useCase->method('__invoke')->willReturn($expectedValues);
        $result = ($this->sut)();
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());
        $this->assertEquals(json_encode($expectedValues), $result->getContent());
    }
}
