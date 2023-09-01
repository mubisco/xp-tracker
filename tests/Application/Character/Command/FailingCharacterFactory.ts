import { CharacterFactory } from '@/Domain/Character/CharacterFactory'
import { CharacterFactoryError } from '@/Domain/Character/CharacterFactoryError'
import { Character } from '@/Domain/Character/Character'

export class FailingCharacterFactory implements CharacterFactory {
  // eslint-disable-next-line
  make (data: { [key: string]: any }): Character {
    throw new CharacterFactoryError('asd')
  }
}
