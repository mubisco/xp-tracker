import { Encounter } from './Encounter'

export interface UpdateEncounterWriteModel {
  update (encounter: Encounter): Promise<void>
}
