import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { Encounter } from '@/Domain/Encounter/Encounter'

export class AddEncounterWriteModelSpy implements AddEncounterWriteModel {
  public shouldFail: boolean = false
  public writeSuccessful: boolean = false
  public encounter: Encounter | null = null

  // eslint-disable-next-line
  async write (encounter: Encounter): Promise<void> {
    if (this.shouldFail) {
      throw new AddEncounterWritelModelError('nop')
    }
    this.encounter = encounter
    this.writeSuccessful = true
  }
}
