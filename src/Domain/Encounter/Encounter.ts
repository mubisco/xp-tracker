import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { EncounterVisitor } from './EncounterVisitor'
import { EncounterMonster } from './Monster/EncounterMonster'

export interface Encounter {
  id(): Ulid
  addMonster (monster: EncounterMonster): void
  removeMonster (monster: EncounterMonster): void
  visit (visitor: EncounterVisitor<any>): any
  totalXp(): number
}
