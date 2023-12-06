import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class NotFoundEncounterRepositoryDummy implements EncounterRepository {
  allEncounters (): Promise<Encounter[]> {
    return Promise.resolve([])
  }
  // eslint-disable-next-line
  byUlid (ulid: Ulid): Promise<Encounter> {
    throw new EncounterNotFoundError()
  }
}
