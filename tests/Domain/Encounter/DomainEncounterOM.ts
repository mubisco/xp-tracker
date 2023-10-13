import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'

export class DomainEncounterOM {
  static random (): DomainEncounter {
    return DomainEncounter.withName(EncounterName.fromString('random'))
  }

  static withName (name: string): DomainEncounter {
    return DomainEncounter.withName(EncounterName.fromString(name))
  }

  static withMonster (name: string, monster: EncounterMonster): DomainEncounter {
    const encounter = DomainEncounter.withName(EncounterName.fromString(name))
    encounter.addMonster(monster)
    return encounter
  }
}
