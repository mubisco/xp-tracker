<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Encounter\Application\Command\AssignPartyToEncounterCommand;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

#[AsController]
#[Route(
    path: "/api/encounter/{encounterUlid}/assign-party/{partyUlid}",
    name: "api_encounter_assign_party",
    methods: "PUT"
)]
final class PutAssignPartyToEncounterController
{
    private const ALLOWED_EXCEPTIONS = [
        'WrongEncounterUlidException' => Response::HTTP_BAD_REQUEST,
        'WrongEncounterPartyUlidException' => Response::HTTP_BAD_REQUEST,
        'EncounterNotFoundException' => Response::HTTP_NOT_FOUND,
        'EncounterPartyNotFoundException' => Response::HTTP_NOT_FOUND,
        'PartyAlreadyAssignedException' => Response::HTTP_CONFLICT,
        'PartyNotAssignedToEncounterException' => Response::HTTP_PRECONDITION_FAILED
    ];

    public function __construct(private readonly JsonCommandBus $bus)
    {
    }

    public function __invoke(string $encounterUlid, string $partyUlid): JsonResponse
    {
        $command = new AssignPartyToEncounterCommand(encounterUlid: $encounterUlid, partyUlid: $partyUlid);
        return $this->bus->process($command, self::ALLOWED_EXCEPTIONS);
    }
}
