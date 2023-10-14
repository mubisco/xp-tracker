import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { EncounterVisitor } from './EncounterVisitor'
import { EncounterMonster } from './Monster/EncounterMonster'
import { EncounterStatus } from './EncounterStatus'
import { EventAware } from '../Shared/Event/EventAware'

export interface Encounter extends EventAware {
  id(): Ulid
  addMonster (monster: EncounterMonster): void
  removeMonster (monster: EncounterMonster): void
  visit (visitor: EncounterVisitor<any>): any
  status(): EncounterStatus
  finish(): void
}
