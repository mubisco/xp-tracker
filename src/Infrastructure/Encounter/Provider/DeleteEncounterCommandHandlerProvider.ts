import { DeleteEncounterCommandHandler } from '@/Application/Encounter/Command/DeleteEncounterCommandHandler'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'

export class DeleteEncounterCommandHandlerProvider {
  provide (): DeleteEncounterCommandHandler {
    const writeModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    return new DeleteEncounterCommandHandler(writeModel, writeModel)
  }
}
