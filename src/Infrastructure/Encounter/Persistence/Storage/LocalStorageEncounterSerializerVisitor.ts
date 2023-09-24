import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'

export class LocalStorageEncounterSerializerVisitor implements EncounterVisitor<string> {
  visitDomainEncounter (encounter: DomainEncounter): string {
    const result = {
      id: encounter.id().value(),
      name: encounter.name().value()
    }
    return JSON.stringify(result)
  }
}
