import { AssignCharacterToPartyWriteModel } from '@/Domain/Party/AssignCharacterToPartyWriteModel'
import { Party } from '@/Domain/Party/Party'
import { PartyWriteModel } from '@/Domain/Party/PartyWriteModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class PartyWriteModelSpy implements PartyWriteModel, AssignCharacterToPartyWriteModel {
  public party:Party | null = null
  public assignedCharacterUlid = ''
  public toAssginPartyUlid = ''

  assingnToParty (characterUlid: Ulid, partyUlid: Ulid): Promise<void> {
    this.assignedCharacterUlid = characterUlid.value()
    this.toAssginPartyUlid = partyUlid.value()
    return Promise.resolve()
  }

  async storeParty (party: Party): Promise<void> {
    this.party = party
    return Promise.resolve()
  }
}
