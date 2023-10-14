import { Ulid } from '../Shared/Identity/Ulid'
import { CharacterVisitor } from './CharacterVisitor'
import { Experience } from './Experience'

export interface Character {
  id (): Ulid
  visit (visitor: CharacterVisitor<any>): any
  addExperience (experience: Experience): void
}
