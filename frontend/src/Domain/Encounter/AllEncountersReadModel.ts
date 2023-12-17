import { EncounterDto } from './EncounterDto'

export interface AllEncountersReadModel {
  all(): Promise<EncounterDto[]>
}
