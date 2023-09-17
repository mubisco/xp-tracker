import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterVisitor } from '@/Domain/Character/CharacterVisitor'

export class DummyCharacterVisitor implements CharacterVisitor<string> {
  visitBasicCharacter (character: BasicCharacter): string {
    return character.id().value()
  }
}
