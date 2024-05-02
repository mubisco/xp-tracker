<?php

namespace XpTracker\Tests\Unit\Encounter\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use XpTracker\Encounter\Application\Command\UnassignPartyToEncounterCommand;
use XpTracker\Encounter\Infrastructure\Entrypoint\Api\PutUnassignPartyToEncounterController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class PutUnassignPartyToEncounterControllerTest extends TestCase
{
    /** @test */
    public function itShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new PutUnassignPartyToEncounterController(new FailingJsonCommandBus());
        $response = ($sut)('01HWTWD9AGJ1T69SKRMF251JPF', '01HWTWDC8897TSZ1367P0K9HRR');
        $this->assertEquals(400, $response->getStatusCode());
    }

    /** @test */
    public function itShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new PutUnassignPartyToEncounterController($spy);
        $response = ($sut)('encounterUlid', 'partyUlid');
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(UnassignPartyToEncounterCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('encounterUlid', $command->encounterUlid);
        $this->assertEquals('partyUlid', $command->partyUlid);
    }
}
