<?php

declare(strict_types=1);

namespace XpTracker\Encounter\Infrastructure\Entrypoint\Api;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Encounter\Application\Command\CreateEncounterCommand;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

#[AsController]
#[Route("/api/encounter", name: "api_encounter_create", methods: "POST")]
final class PostCreateEncounterController
{
    private const ALLOWED_EXCEPTIONS = [
        'InvalidArgumentException' => Response::HTTP_BAD_REQUEST,
        'EncounterAlreadyExistsException' => Response::HTTP_UNPROCESSABLE_ENTITY
    ];

    public function __construct(private readonly JsonCommandBus $jsonCommandBus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $command = $this->parseRequest($request);
            return $this->jsonCommandBus->process($command, self::ALLOWED_EXCEPTIONS);
        } catch (JsonException) {
            return new JsonResponse(['message' => 'Malformed JSON body'], Response::HTTP_BAD_REQUEST);
        }
    }

    private function parseRequest(Request $request): CreateEncounterCommand
    {
        $rawContent = $request->getContent();
        $parsedRequest = json_decode($rawContent, false, 512, JSON_THROW_ON_ERROR);
        return new CreateEncounterCommand(
            $parsedRequest->ulid ?? '',
            $parsedRequest->name ?? ''
        );
    }
}
