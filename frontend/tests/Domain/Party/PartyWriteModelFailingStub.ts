import { Party } from '@/Domain/Party/Party';
import { PartyWriteModel } from '@/Domain/Party/PartyWriteModel'
import { PartyWriteModelError } from '@/Domain/Party/PartyWriteModelError';

export class PartyWriteModelFailingStub implements PartyWriteModel {
  // eslint-disable-next-line
  async storeParty(party: Party): Promise<void> {
    return Promise.reject(new PartyWriteModelError())
  }
}
