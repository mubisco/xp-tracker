import { AddMonsterToEncounterCommandHandler } from '@/Application/Encounter/Command/AddMonsterToEncounterCommandHandler'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'

export class AddMonsterCommandHandlerProvider {
  provide (): AddMonsterToEncounterCommandHandler {
    const writeModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    return new AddMonsterToEncounterCommandHandler(writeModel, writeModel)
  }
}
