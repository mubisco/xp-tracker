import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DeleteCharacterCommand } from './DeleteCharacterCommand'
import { DeleteCharacterWriteModel } from '@/Domain/Character/DeleteCharacterWriteModel'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'

export class DeleteCharacterCommandHandler {
  /* eslint-disable no-useless-constructor */
  constructor (
    private readonly writeModel: DeleteCharacterWriteModel,
    private readonly eventBus: EventBus
  ) {
  }

  async handle (command: DeleteCharacterCommand): Promise<void> {
    const characterUlid = Ulid.fromString(command.characterId)
    await this.writeModel.remove(characterUlid)
    this.eventBus.publish([new PartyWasUpdated()])
    return Promise.resolve()
  }
}
