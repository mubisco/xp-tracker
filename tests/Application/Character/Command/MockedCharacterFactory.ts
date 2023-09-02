import { CharacterFactory } from '@/Domain/Character/CharacterFactory'
import { Character } from '@/Domain/Character/Character'

class DummyCharacter implements Character {}

export class MockedCharacterFactory implements CharacterFactory {
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  make (data: { [key: string]: any }): Character {
    return new DummyCharacter()
  }
}
