import { Character } from '@/Domain/Party/Character/Character'
import { CharacterWriteModelError } from '@/Domain/Party/Character/CharacterWriteModelError'
import { CreateCharacterWriteModel } from '@/Domain/Party/Character/CreateCharacterWriteModel'

export class CharacterWriteModelFailingStub implements CreateCharacterWriteModel {
  // eslint-disable-next-line
  createCharacter (character: Character): Promise<void> {
    throw new CharacterWriteModelError()
  }
}
