import { Party } from '@/Domain/Character/Party/Party'
import { UpdateExperiencePartyWriteModel } from '@/Domain/Character/Party/UpdateExperiencePartyWriteModel'

export class UpdateExperiencePartyWriteModelSpy implements UpdateExperiencePartyWriteModel {
  public updatedPoints = 0
  async updateParty (party: Party): Promise<void> {
    // @ts-ignore
    this.updatedPoints = party.experiencePoints
    return Promise.resolve()
  }
}
