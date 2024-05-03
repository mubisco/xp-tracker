import { EncounterForPartyWriteModel } from '@/Domain/Encounter/EncounterForPartyWriteModel'
import { SimpleEncounter } from '@/Domain/Encounter/SimpleEncounter'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class EncounterForPartyWriteModelSpy implements EncounterForPartyWriteModel {
  public storedPartyUlid = ''
  public storedEncounter: SimpleEncounter | null = null

  // eslint-disable-next-line
  createForParty (partyUlid: Ulid, encounter: SimpleEncounter): Promise<void> {
    this.storedPartyUlid = partyUlid.value()
    this.storedEncounter = encounter
    return Promise.resolve()
  }
}
