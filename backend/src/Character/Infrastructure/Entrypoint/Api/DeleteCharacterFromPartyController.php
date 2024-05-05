<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Entrypoint\Api;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Character\Application\Command\DeleteCharacterFromPartyCommand;
use XpTracker\Shared\Infrastructure\Symfony\JsonCommandBus;

#[AsController]
#[Route("/api/party/{partyUlid}/remove/{characterUlid}", name: "api_party_remove_character", methods: "DELETE")]
final class DeleteCharacterFromPartyController
{
    private const ALLOWED_EXCEPTIONS = [
        'InvalidArgumentException' => Response::HTTP_BAD_REQUEST,
        'PartyNotFoundException' => Response::HTTP_NOT_FOUND,
        'CharacterNotFoundExceptions' => Response::HTTP_NOT_FOUND,
        'CharacterNotInPartyException' => Response::HTTP_UNPROCESSABLE_ENTITY,
        'CharacterAlreadyInPartyException' => Response::HTTP_CONFLICT
    ];

    public function __construct(private readonly JsonCommandBus $jsonCommandBus)
    {
    }

    public function __invoke(string $partyUlid, string $characterUlid): JsonResponse
    {
        try {
            $command = new DeleteCharacterFromPartyCommand($partyUlid, $characterUlid);
            return $this->jsonCommandBus->process($command, self::ALLOWED_EXCEPTIONS);
        } catch (JsonException) {
            return new JsonResponse(['message' => 'Malformed JSON body'], Response::HTTP_BAD_REQUEST);
        }
    }
}
