import { AddCharacterWriteModel } from '@/Domain/Character/AddCharacterWriteModel'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { Character } from '@/Domain/Character/Character'

export class AddCharacterWriteModelSpy implements AddCharacterWriteModel {
  private _characters: Character[] = []
  public shouldFail: boolean = false

  public invoke (character: Character): Promise<void> {
    if (this.shouldFail) {
      return Promise.reject(new CharacterWriteModelError('ops'))
    }
    this._characters.forEach((currentCharacter: Character) => {
      if (currentCharacter.id().equals(character.id())) {
        return Promise.reject(new CharacterWriteModelError('ops'))
      }
    })
    this._characters.push(character)
    return Promise.resolve()
  }

  public wasAdded (): boolean {
    return this._characters.length > 0
  }
}
