import { CharactersByPartyIdQueryHandler } from '@/Application/Party/Query/Character/CharactersByPartyIdQueryHandler'
import { FetchCharacterClient } from '../Persistence/Fetch/FetchCharacterClient'

const baseUrl = import.meta.env.VITE_API_URL

export class CharactersByPartyIdQueryHandlerProvider {
  provide (): CharactersByPartyIdQueryHandler {
    const client = new FetchCharacterClient(baseUrl)
    return new CharactersByPartyIdQueryHandler(client)
  }
}
