import { AddCharacterCommand } from './AddCharacterCommand'
import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { Experience } from '@/Domain/Character/Experience'
import { Health } from '@/Domain/Character/Health'
import { AddCharacterWriteModel } from '@/Domain/Character/AddCharacterWriteModel'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'

export class AddCharacterCommandHandler {
  private _writeModel: AddCharacterWriteModel
  private _eventBus: EventBus

  constructor (
    writeModel: AddCharacterWriteModel,
    eventBus: EventBus
  ) {
    this._writeModel = writeModel
    this._eventBus = eventBus
  }

  async handle (command: AddCharacterCommand): Promise<void> {
    try {
      const character = BasicCharacter.fromValues(
        CharacterName.fromString(command.name),
        Experience.fromXp(command.actualXp),
        Health.fromMaxHp(command.maxHp)
      )
      await this._writeModel.invoke(character)
      this._eventBus.publish([new PartyWasUpdated()])
      return Promise.resolve()
    } catch (e) {
      return Promise.reject(e)
    }
  }
}
