import { DeleteCharacterCommandHandler } from '@/Application/Character/Command/DeleteCharacterCommandHandler'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'

export class DeleteCharacterCommandHandlerProvider {
  provide (eventBus: EventBus): DeleteCharacterCommandHandler {
    const repository = new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
    return new DeleteCharacterCommandHandler(repository, eventBus)
  }
}
