import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'

export class SimpleStringEncounterVisitor implements EncounterVisitor<string> {
  visitDomainEncounter (encounter: DomainEncounter): string {
    return encounter.id().value()
  }
}
