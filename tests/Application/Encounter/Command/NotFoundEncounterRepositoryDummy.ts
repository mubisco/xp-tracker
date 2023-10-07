import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class NotFoundEncounterRepositoryDummy implements EncounterRepository {
  // eslint-disable-next-line
  byId (ulid: Ulid): Promise<Encounter> {
    throw new EncounterNotFoundError()
  }
}
