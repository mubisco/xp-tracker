import { AddEncounterCommandHandler } from '@/Application/Encounter/Command/AddEncounterCommandHandler'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'

export class AddEncounterCommandHandlerProvider {
  provide (): AddEncounterCommandHandler {
    const writeModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    return new AddEncounterCommandHandler(writeModel)
  }
}
