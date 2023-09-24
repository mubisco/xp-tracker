import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { Encounter } from '@/Domain/Encounter/Encounter'

export class AddEncounterWriteModelSpy implements AddEncounterWriteModel {
  public shouldFail: boolean = false
  public writeSuccessful: boolean = false

  // eslint-disable-next-line
  async write (encounter: Encounter): Promise<void> {
    if (this.shouldFail) {
      throw new AddEncounterWritelModelError('nop')
    }
    this.writeSuccessful = true
  }
}
