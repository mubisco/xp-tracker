import { Ulid } from '../Shared/Identity/Ulid'
import { CharacterVisitor } from './CharacterVisitor'

export interface Character {
  id (): Ulid
  visit (visitor: CharacterVisitor<any>): any
}
