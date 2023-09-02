import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { Character } from '@/Domain/Character/Character'
import { CharacterFactory } from '@/Domain/Character/CharacterFactory'
import { CharacterFactoryError } from '@/Domain/Character/CharacterFactoryError'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { Experience } from '@/Domain/Character/Experience'
import { Health } from '@/Domain/Character/Health'

export class FormDataCharacterFactory implements CharacterFactory {
  make (data: { [key: string]: any }): Character {
    try {
      return BasicCharacter.fromValues(
        CharacterName.fromString(data?.name ?? ''),
        Experience.fromXp(data?.xp ?? -1),
        Health.fromMaxHp(data?.hp ?? -1)
      )
    } catch (error) {
      if (error instanceof RangeError) {
        throw new CharacterFactoryError(error.message)
      }
      throw error
    }
  }
}
