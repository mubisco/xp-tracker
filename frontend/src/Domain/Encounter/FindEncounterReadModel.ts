import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { EncounterDto } from './EncounterDto'

export interface FindEncounterReadModel {
  byId (ulid: Ulid): Promise<EncounterDto>
}
