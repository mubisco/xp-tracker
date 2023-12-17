import { FetchCharactersQueryHandler } from '@/Application/Character/Query/FetchCharactersQueryHandler'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'

export class FetchCharactersQueryHandlerProvider {
  provide (): FetchCharactersQueryHandler {
    const repository = new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
    return new FetchCharactersQueryHandler(repository)
  }
}
