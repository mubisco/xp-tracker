import { Party } from '@/Domain/Party/Party';
import { PartyWriteModel } from '@/Domain/Party/PartyWriteModel'

export class PartyWriteModelSpy implements PartyWriteModel {
  public party:Party | null = null;
  async storeParty(party: Party): Promise<void> {
    this.party = party
    return Promise.resolve();
  }
}
