import { AddEncounterCommandHandler } from '@/Application/Encounter/Command/AddEncounterCommandHandler'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'
import { LocalStoragePartyTresholdsRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStoragePartyTresholdsReadModel'

export class AddEncounterCommandHandlerProvider {
  provide (): AddEncounterCommandHandler {
    const writeModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    const partyTresholdReadModel = new LocalStoragePartyTresholdsRepository()
    return new AddEncounterCommandHandler(writeModel, partyTresholdReadModel)
  }
}
