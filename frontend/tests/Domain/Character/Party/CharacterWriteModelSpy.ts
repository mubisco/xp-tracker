import { Character } from '@/Domain/Party/Character/Character'
import { CreateCharacterWriteModel } from '@/Domain/Party/Character/CreateCharacterWriteModel'

export class CharacterWriteModelSpy implements CreateCharacterWriteModel {
  public storedCharacter: Character | null = null
  createCharacter (character: Character): Promise<void> {
    this.storedCharacter = character
    return Promise.resolve()
  }
}
