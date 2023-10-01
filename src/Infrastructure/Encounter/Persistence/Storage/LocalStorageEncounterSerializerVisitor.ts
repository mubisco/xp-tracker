import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'

export class LocalStorageEncounterSerializerVisitor implements EncounterVisitor<string> {
  // TODO To simplify maintanibility, this visitor shouls generate a EncounterDto structure
  // so the read model was just to parse the stored item
  visitDomainEncounter (encounter: DomainEncounter): string {
    const result = {
      ulid: encounter.id().value(),
      name: encounter.name().value()
    }
    return JSON.stringify(result)
  }
}
