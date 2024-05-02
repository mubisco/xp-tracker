import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export interface AssignCharacterToPartyWriteModel {
  assingnToParty(characterUlid: Ulid, partyUlid: Ulid): Promise<void>;
}
