import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { Experience } from '@/Domain/Character/Experience'
import { Health } from '@/Domain/Character/Health'

export class BasicCharacterOM {
  static random (): BasicCharacter {
    return BasicCharacter.fromValues(
      CharacterName.fromString('Darling'),
      Experience.fromXp(345),
      Health.fromMaxHp(25)
    )
  }
}
