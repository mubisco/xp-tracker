<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Entrypoint\Api;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Character\Application\Command\AddCharacterToPartyCommand;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

#[AsController]
#[Route("/api/party/character/add", name: "api_party_character_add", methods: "PUT")]
final class PutAddCharacterToPartyController
{
    private const ALLOWED_EXCEPTIONS = [
        'InvalidArgumentException' => Response::HTTP_BAD_REQUEST,
        'PartyNotFoundException' => Response::HTTP_NOT_FOUND,
        'CharacterNotFoundException' => Response::HTTP_NOT_FOUND,
        'CharacterAlreadyInPartyException' => Response::HTTP_CONFLICT,
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

    private function parseRequest(Request $request): AddCharacterToPartyCommand
    {
        $rawContent = $request->getContent();
        $parsedRequest = json_decode($rawContent, false, 512, JSON_THROW_ON_ERROR);
        return new AddCharacterToPartyCommand(
            partyUlid: $parsedRequest->partyUlid ?? '',
            characterUlid: $parsedRequest->characterUlid ?? ''
        );
    }
}
