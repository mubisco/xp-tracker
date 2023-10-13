import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'
import { AllEncountersQueryHandler } from '@/Application/Encounter/Query/AllEncountersQueryHandler'

export class AllEncountersQueryHandlerProvider {
  provide (): AllEncountersQueryHandler {
    const readModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    return new AllEncountersQueryHandler(readModel)
  }
}
