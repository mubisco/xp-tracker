import { Encounter } from './Encounter'

export interface DeleteEncounterWriteModel {
  remove (encounter: Encounter): Promise<void>
}
