import { DeleteCharacterCommandHandler } from '@/Application/Character/Command/DeleteCharacterCommandHandler'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'

export class DeleteCharacterCommandHandlerProvider {
  provide (): DeleteCharacterCommandHandler {
    const repository = new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
    return new DeleteCharacterCommandHandler(repository)
  }
}
