import { UpdateEncounterLevelsWhenPartyUpdatedEventHandler } from '@/Application/Encounter/Event/UpdateEncounterLevelsWhenPartyUpdated'
import { LocalStoragePartyTresholdsRepository } from '../Persistence/Storage/LocalStoragePartyTresholdsReadModel'
import { LocalStorageEncounterRepository } from '../Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '../Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { LocalStorageEncounterFactory } from '../Persistence/Storage/LocalStorageEncounterFactory'

export class UpdateEncounterLevelsWhenPartyUpdatedProvider {
  provide (): UpdateEncounterLevelsWhenPartyUpdatedEventHandler {
    const partyTresholdReadModel = new LocalStoragePartyTresholdsRepository()
    const encounterRepository = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    return new UpdateEncounterLevelsWhenPartyUpdatedEventHandler(
      partyTresholdReadModel,
      encounterRepository,
      encounterRepository
    )
  }
}
