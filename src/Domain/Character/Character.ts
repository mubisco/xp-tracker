import { CharacterId } from './CharacterId'
import { CharacterVisitor } from './CharacterVisitor'

export interface Character {
  id (): CharacterId
  visit (visitor: CharacterVisitor<any>): any
}
