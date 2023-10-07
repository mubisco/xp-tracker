import { Encounter } from '@/Domain/Encounter/Encounter'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'

export class FailingUpdateEncounterWriteModel implements UpdateEncounterWriteModel {
  // eslint-disable-next-line
  async update (encounter: Encounter): Promise<void> {
    throw new EncounterWriteModelError()
  }
}
