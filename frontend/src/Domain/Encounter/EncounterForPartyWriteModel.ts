import { Ulid } from '../Shared/Identity/Ulid'
import { SimpleEncounter } from './SimpleEncounter'

export interface EncounterForPartyWriteModel {
  createForParty(partyUlid: Ulid, encounter: SimpleEncounter): Promise<void>;
}
