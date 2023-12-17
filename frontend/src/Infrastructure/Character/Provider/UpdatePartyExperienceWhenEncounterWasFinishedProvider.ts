import { UpdatePartyExperienceWhenEncounterWasFinishedEventHandler } from '@/Application/Character/Event/UpdatePartyExperienceWhenEncounterWasFinishedEventHandler'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { EventBus } from '@/Domain/Shared/Event/EventBus'

export class UpdatePartyExperienceWhenEncounterWasFinishedProvider {
  provide (eventBus: EventBus): UpdatePartyExperienceWhenEncounterWasFinishedEventHandler {
    const repository = new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
    return new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(repository, repository, eventBus)
  }
}
