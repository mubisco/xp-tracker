import { FinishEncounterCommandHandler } from '@/Application/Encounter/Command/FinishEncounterCommandHandler'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'

export class FinishEncounterCommandHandlerProvider {
  provide (eventBus: EventBus): FinishEncounterCommandHandler {
    const writeModel = new LocalStorageEncounterRepository(
      new LocalStorageEncounterSerializerVisitor(),
      new LocalStorageEncounterFactory()
    )
    return new FinishEncounterCommandHandler(writeModel, writeModel, eventBus)
  }
}
