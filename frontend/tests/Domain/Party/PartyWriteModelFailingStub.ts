import { AssignCharacterToPartyWriteModel } from '@/Domain/Party/AssignCharacterToPartyWriteModel'
import { Party } from '@/Domain/Party/Party'
import { PartyWriteModel } from '@/Domain/Party/PartyWriteModel'
import { PartyWriteModelError } from '@/Domain/Party/PartyWriteModelError'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class PartyWriteModelFailingStub implements PartyWriteModel, AssignCharacterToPartyWriteModel {
  // eslint-disable-next-line
  assingnToParty (characterUlid: Ulid, partyUlid: Ulid): Promise<void> {
    return Promise.reject(new PartyWriteModelError())
  }
  // eslint-disable-next-line
  async storeParty(party: Party): Promise<void> {
    return Promise.reject(new PartyWriteModelError())
  }
}
