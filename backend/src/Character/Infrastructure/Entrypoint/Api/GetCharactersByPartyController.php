<?php

declare(strict_types=1);

namespace XpTracker\Character\Infrastructure\Entrypoint\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use XpTracker\Character\Application\Query\CharactersByPartyQuery;
use XpTracker\Character\Application\Query\CharactersByPartyQueryHandler;
use XpTracker\Character\Domain\Party\InvalidPartyUlidValueException;
use XpTracker\Character\Domain\Party\PartyNotFoundException;

#[AsController]
#[Route("/api/party/{partyId}/characters", name: "api_party_get_characters", methods: ['GET'])]
final class GetCharactersByPartyController
{
    public function __construct(private readonly CharactersByPartyQueryHandler $useCase)
    {
    }

    public function __invoke(string $partyId): JsonResponse
    {
        try {
            $query = new CharactersByPartyQuery($partyId);
            $result = ($this->useCase)($query);
            return new JsonResponse($result, Response::HTTP_OK);
        } catch (PartyNotFoundException) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        } catch (InvalidPartyUlidValueException) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
    }
}
