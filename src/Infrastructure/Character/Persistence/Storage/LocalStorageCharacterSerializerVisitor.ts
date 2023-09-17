import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterVisitor } from '@/Domain/Character/CharacterVisitor'

export class LocalStorageCharacterSerializerVisitor implements CharacterVisitor<string> {
  visitBasicCharacter (character: BasicCharacter): string {
    const id = character.id().value()
    const name = character.name().value()
    const hitpoints = character.hitPoints()
    const experience = character.experience()
    const data = {
      id,
      name,
      hitpoints: {
        max: hitpoints.max,
        current: hitpoints.current
      },
      experiencePoints: experience.actual
    }
    return JSON.stringify(data)
  }
}
