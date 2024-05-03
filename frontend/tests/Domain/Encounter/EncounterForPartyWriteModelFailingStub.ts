import { EncounterForPartyWriteModel } from '@/Domain/Encounter/EncounterForPartyWriteModel'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { SimpleEncounter } from '@/Domain/Encounter/SimpleEncounter'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class EncounterForPartyWriteModelFailingStub implements EncounterForPartyWriteModel {
  // eslint-disable-next-line
  createForParty (partyUlid: Ulid, encounter: SimpleEncounter): Promise<void> {
    throw new EncounterWriteModelError()
  }
}
