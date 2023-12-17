import { FindEncounterByIdQueryHandler } from '@/Application/Encounter/Query/FindEncounterByIdQueryHandler'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'

export class FindEncounterByIdQueryHandlerProvider {
  provide (): FindEncounterByIdQueryHandler {
    const readModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    return new FindEncounterByIdQueryHandler(readModel)
  }
}
