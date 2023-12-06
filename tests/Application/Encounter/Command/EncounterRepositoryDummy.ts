import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'
import { EncounterMonsterOM } from '@tests/Domain/Encounter/EncounterMonsterOM'

export class EncounterRepositoryDummy implements EncounterRepository {
  allEncounters (): Promise<Encounter[]> {
    const encounters: Encounter[] = []
    const monster = EncounterMonsterOM.random()
    const encounter = DomainEncounterOM.withMonster('Encounter 1', monster)
    encounters.push(encounter)
    return Promise.resolve(encounters)
  }
  // eslint-disable-next-line
  byUlid (ulid: Ulid): Promise<Encounter> {
    const monster = EncounterMonsterOM.random()
    const encounter = DomainEncounterOM.withMonster('Encounter 1', monster)
    return Promise.resolve(encounter)
  }
}
