import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterName } from '@/Domain/Encounter/EncounterName'

export class DomainEncounterOM {
  static random (): DomainEncounterOM {
    return DomainEncounter.withName(EncounterName.fromString('random'))
  }

  static withName (name: string): DomainEncounter {
    return DomainEncounter.withName(EncounterName.fromString(name))
  }
}
