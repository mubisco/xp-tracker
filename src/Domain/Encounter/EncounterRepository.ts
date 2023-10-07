import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { Encounter } from './Encounter'

export interface EncounterRepository {
  byId (ulid: Ulid): Promise<Encounter>
}
