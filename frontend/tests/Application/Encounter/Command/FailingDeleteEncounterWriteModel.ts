import { DeleteEncounterWriteModel } from '@/Domain/Encounter/DeleteEncounterWriteModel'
import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'

export class FailingDeleteEncounterWriteModel implements DeleteEncounterWriteModel {
  // eslint-disable-next-line
  async remove(encounter: Encounter): Promise<void> {
    throw new EncounterWriteModelError()
  }
}
