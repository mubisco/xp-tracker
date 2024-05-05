<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Entrypoint\Api;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Encounter\Application\Command\DeleteEncounterCommand;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

#[AsController]
#[Route("/api/encounter/delete/{encounterUlid}", name: "api_encounter_delete", methods: "DELETE")]
final class DeleteEncounterController
{
    private const ALLOWED_EXCEPTIONS = [
        'WrongEncounterUlidException' => Response::HTTP_BAD_REQUEST,
        'EncounterNotFoundException' => Response::HTTP_NOT_FOUND
    ];

    public function __construct(private readonly JsonCommandBus $bus)
    {
    }

    public function __invoke(string $encounterUlid): JsonResponse
    {
        try {
            $command = new DeleteEncounterCommand($encounterUlid);
            return $this->bus->process($command, self::ALLOWED_EXCEPTIONS);
        } catch (JsonException) {
            return new JsonResponse(['message' => 'Malformed JSON body'], Response::HTTP_BAD_REQUEST);
        }
    }
}
