import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { Encounter } from '@/Domain/Encounter/Encounter'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'

export class UpdateEncounterWriteModelSpy implements UpdateEncounterWriteModel {
  private encounter: Encounter | null = null

  update (encounter: Encounter): Promise<void> {
    this.encounter = encounter
    return Promise.resolve()
  }

  getUpdatedEncounter (): DomainEncounter {
    if (this.encounter === null) {
      throw new Error('No updated encounter')
    }
    return this.encounter as DomainEncounter
  }
}
