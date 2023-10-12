import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class LocalStorageEncounterFactory {
  make (encounterData: EncounterDto): DomainEncounter {
    const encounter = DomainEncounter.withName(EncounterName.fromString(encounterData.name))
    encounter.ulid = Ulid.fromString(encounterData.ulid)
    return encounter
  }
}
