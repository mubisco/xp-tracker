import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'

export class EncounterRepositoryDummy implements EncounterRepository {
  byId (ulid: Ulid): Promise<Encounter> {
    return Promise.resolve(DomainEncounterOM.random())
  }
}
