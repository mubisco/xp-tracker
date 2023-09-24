import { AddEncounterCommandHandler } from '@/Application/Encounter/Command/AddEncounterCommandHandler'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'

export class AddEncounterCommandHandlerProvider {
  provide (): AddEncounterCommandHandler {
    const writeModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor()
    )
    return new AddEncounterCommandHandler(writeModel)
  }
}
