import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { EncounterRepositoryError } from '@/Domain/Encounter/EncounterRepositoryError'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class FailingEncounterRepositoryDummy implements EncounterRepository {
  // eslint-disable-next-line
  byUlid (ulid: Ulid): Promise<Encounter> {
    throw new EncounterRepositoryError()
  }

  allEncounters (): Promise<Encounter[]> {
    throw new EncounterRepositoryError()
  }
}
