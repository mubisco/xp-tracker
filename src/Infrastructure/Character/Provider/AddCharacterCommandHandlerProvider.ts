import { AddCharacterCommandHandler } from '@/Application/Character/Command/AddCharacterCommandHandler'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'

export class AddCharacterCommandHandlerProvider {
  provide (): AddCharacterCommandHandler {
    const repository = new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
    return new AddCharacterCommandHandler(repository)
  }
}
