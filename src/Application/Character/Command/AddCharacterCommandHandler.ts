import { AddCharacterCommand } from './AddCharacterCommand'
import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { Experience } from '@/Domain/Character/Experience'
import { Health } from '@/Domain/Character/Health'
import { AddCharacterWriteModel } from '@/Domain/Character/AddCharacterWriteModel'

export class AddCharacterCommandHandler {
  private _writeModel: AddCharacterWriteModel

  constructor (writeModel: AddCharacterWriteModel) {
    this._writeModel = writeModel
  }

  async handle (command: AddCharacterCommand): Promise<void> {
    try {
      const character = BasicCharacter.fromValues(
        CharacterName.fromString(command.name),
        Experience.fromXp(command.actualXp),
        Health.fromMaxHp(command.maxHp)
      )
      await this._writeModel.invoke(character)
      return Promise.resolve()
    } catch (e) {
      return Promise.reject(e)
    }
  }
}
