import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { Encounter } from './Encounter'

export interface EncounterRepository {
  byUlid (ulid: Ulid): Promise<Encounter>
  allEncounters (): Promise<Encounter[]>
}
