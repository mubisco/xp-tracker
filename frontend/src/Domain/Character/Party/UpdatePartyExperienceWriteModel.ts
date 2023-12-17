import { Party } from '@/Domain/Character/Party/Party'

export interface UpdatePartyExperienceWriteModel {
  update (party: Party): Promise<void>
}
