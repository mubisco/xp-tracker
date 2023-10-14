import { DeleteEncounterWriteModel } from '@/Domain/Encounter/DeleteEncounterWriteModel'
import { Encounter } from '@/Domain/Encounter/Encounter'

export class DeleteEncounterWriteModelSpy implements DeleteEncounterWriteModel {
  public called = 0

  // eslint-disable-next-line
  remove (encounter: Encounter): Promise<void> {
    this.called++
    return Promise.resolve()
  }
}
