<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Entrypoint\Api;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Encounter\Application\Command\ResolveEncounterCommand;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

#[AsController]
#[Route("/api/encounter/resolve", name: "api_encounter_resolve", methods: "PUT")]
final class PutResolveEncounterController
{
    private const ALLOWED_EXCEPTIONS = [
        'WrongEncounterUlidException' => Response::HTTP_BAD_REQUEST,
        'EncounterNotFoundException' => Response::HTTP_NOT_FOUND,
        'EncounterNotResolvableException' => Response::HTTP_CONFLICT
    ];

    public function __construct(private readonly JsonCommandBus $bus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $command = $this->parseRequest($request);
            return $this->bus->process($command, self::ALLOWED_EXCEPTIONS);
        } catch (JsonException) {
            return new JsonResponse(['message' => 'Malformed JSON body'], Response::HTTP_BAD_REQUEST);
        }
    }

    private function parseRequest(Request $request): ResolveEncounterCommand
    {
        $rawContent = $request->getContent();
        $parsedRequest = json_decode($rawContent, false, 512, JSON_THROW_ON_ERROR);
        return new ResolveEncounterCommand(
            encounterUlid: $parsedRequest->encounterUlid ?? ''
        );
    }
}
