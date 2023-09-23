import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DeleteCharacterCommand } from './DeleteCharacterCommand'
import { DeleteCharacterWriteModel } from '@/Domain/Character/DeleteCharacterWriteModel'

export class DeleteCharacterCommandHandler {
  /* eslint-disable no-useless-constructor */
  constructor (private readonly writeModel: DeleteCharacterWriteModel) {
  }

  async handle (command: DeleteCharacterCommand): Promise<void> {
    const characterUlid = Ulid.fromString(command.characterId)
    return this.writeModel.remove(characterUlid)
  }
}
