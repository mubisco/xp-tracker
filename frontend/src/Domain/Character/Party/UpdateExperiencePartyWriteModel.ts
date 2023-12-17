import { Party } from './Party'

export interface UpdateExperiencePartyWriteModel {
  updateParty(party: Party): Promise<void>
}
