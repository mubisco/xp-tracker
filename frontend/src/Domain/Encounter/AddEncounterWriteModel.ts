import { Encounter } from './Encounter'

export interface AddEncounterWriteModel {
  write (encounter: Encounter): Promise<void>
}
