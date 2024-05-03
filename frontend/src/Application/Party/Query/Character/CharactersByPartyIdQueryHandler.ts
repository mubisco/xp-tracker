import { SimpleCharacter } from '@/Domain/Party/Character/SimpleCharacter'
import { CharactersByPartyIdQuery } from './CharactersByPartyIdQuery'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { SimpleCharacterReadModel } from '@/Domain/Party/Character/SimpleCharacterReadModel'

export class CharactersByPartyIdQueryHandler {
  // eslint-disable-next-line
  constructor (private readonly readModel: SimpleCharacterReadModel) {}

  async handle (query: CharactersByPartyIdQuery): Promise<SimpleCharacter[]> {
    const ulid = Ulid.fromString(query.partyUlid)
    return await this.readModel.ofPartyId(ulid)
  }
}
