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
      maxHitpoints: hitpoints.max,
      currentHitpoints: hitpoints.current,
      experiencePoints: experience.actual,
      level: experience.level,
      nextLevel: experience.nextLevel
    }
    return JSON.stringify(data)
  }
}
