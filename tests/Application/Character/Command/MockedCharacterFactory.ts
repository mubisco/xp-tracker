import { CharacterFactory } from '@/Domain/Character/CharacterFactory'
import { Character } from '@/Domain/Character/Character'

export class MockedCharacterFactory implements CharacterFactory {
  // eslint-disable-next-line
  make (data: { [key: string]: any }): Character {
    return new Character()
  }
}
