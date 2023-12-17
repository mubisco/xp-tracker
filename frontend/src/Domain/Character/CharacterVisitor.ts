import { BasicCharacter } from './BasicCharacter'

export interface CharacterVisitor<T> {
  visitBasicCharacter(character: BasicCharacter): T
}
