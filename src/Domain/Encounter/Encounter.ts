import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { EncounterVisitor } from './EncounterVisitor'

export interface Encounter {
  id(): Ulid
  visit (visitor: EncounterVisitor<any>): any
}
