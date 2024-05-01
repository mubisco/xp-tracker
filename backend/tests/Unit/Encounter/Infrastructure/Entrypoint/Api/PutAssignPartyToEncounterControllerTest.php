<?php

namespace XpTracker\Tests\Unit\Encounter\Infrastructure\Entrypoint\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use XpTracker\Encounter\Application\Command\AssignPartyToEncounterCommand;
use XpTracker\Encounter\Infrastructure\Entrypoint\Api\PutAssignPartyToEncounterController;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\FailingJsonCommandBus;
use XpTracker\Tests\Unit\Shared\Infrastructure\Symfony\SpyJsonCommandBus;

class PutAssignPartyToEncounterControllerTest extends TestCase
{
    /** @test */
    public function itShouldThrowErrorOnBadRequestContent(): void
    {
        $sut = new PutAssignPartyToEncounterController(new FailingJsonCommandBus());
        $response = ($sut)('01HWTWD9AGJ1T69SKRMF251JPF', '01HWTWDC8897TSZ1367P0K9HRR');
        $this->assertEquals(400, $response->getStatusCode());
    }

    /** @test */
    public function itShouldReturnOkResponse(): void
    {
        $spy = new SpyJsonCommandBus();
        $sut = new PutAssignPartyToEncounterController($spy);
        $response = ($sut)('encounterUlid', 'partyUlid');
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(AssignPartyToEncounterCommand::class, $spy->message);
        $command = $spy->message;
        $this->assertEquals('encounterUlid', $command->encounterUlid);
        $this->assertEquals('partyUlid', $command->partyUlid);
    }
}
