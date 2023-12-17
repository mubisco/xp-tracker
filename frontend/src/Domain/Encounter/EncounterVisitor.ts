import { DomainEncounter } from './DomainEncounter'

export interface EncounterVisitor<T> {
  visitDomainEncounter(encounter: DomainEncounter): T
}
