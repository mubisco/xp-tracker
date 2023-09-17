import { CharacterDto } from '@/Domain/Character/CharacterDto'
import { FetchCharactersQuery } from './FetchCharactersQuery'
import { CharacterListReadModel } from '@/Domain/Character/CharacterListReadModel'

export class FetchCharactersQueryHandler {
  // eslint-disable-next-line
  constructor (private readonly readModel: CharacterListReadModel) {}

  // eslint-disable-next-line
  invoke (query: FetchCharactersQuery): Promise<CharacterDto[]> {
    return this.readModel.invoke()
  }
}
