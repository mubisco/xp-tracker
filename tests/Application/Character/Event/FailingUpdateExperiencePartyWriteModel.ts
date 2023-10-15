import { Party } from '@/Domain/Character/Party/Party'
import { PartyWriteModelError } from '@/Domain/Character/Party/PartyWriteModelError'
import { UpdateExperiencePartyWriteModel } from '@/Domain/Character/Party/UpdateExperiencePartyWriteModel'

export class FailingUpdateExperiencePartyWriteModel implements UpdateExperiencePartyWriteModel {
  // eslint-disable-next-line
  async updateParty (party: Party): Promise<void> {
    throw new PartyWriteModelError('Opsie')
  }
}
