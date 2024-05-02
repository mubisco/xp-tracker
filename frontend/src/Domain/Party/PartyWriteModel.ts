import { Party } from '@/Domain/Party/Party'

export interface PartyWriteModel {
  storeParty(party: Party): Promise<void>;
}
