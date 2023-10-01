import { FindEncounterByIdQueryHandler } from '@/Application/Encounter/Query/FindEncounterByIdQueryHandler'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'

export class FindEncounterByIdQueryHandlerProvider {
  provide (): FindEncounterByIdQueryHandler {
    const readModel = new LocalStorageEncounterRepository(new LocalStorageEncounterSerializerVisitor())
    return new FindEncounterByIdQueryHandler(readModel)
  }
}
