import { UpdatePartyExperienceWhenEncounterWasFinishedEventHandler } from '@/Application/Character/Event/UpdatePartyExperienceWhenEncounterWasFinishedEventHandler'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'

export class UpdatePartyExperienceWhenEncounterWasFinishedProvider {
  provide (): UpdatePartyExperienceWhenEncounterWasFinishedEventHandler {
    const repository = new LocalStorageCharacterRepository(new LocalStorageCharacterSerializerVisitor())
    return new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(repository, repository)
  }
}
