import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { SimpleCharacter } from './SimpleCharacter'

export interface SimpleCharacterReadModel {
  ofPartyId (partyUlid: Ulid): Promise<SimpleCharacter[]>
}
