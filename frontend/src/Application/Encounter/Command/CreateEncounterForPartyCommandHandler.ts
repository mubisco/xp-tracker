import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { CreateEncounterForPartyCommand } from './CreateEncounterForPartyCommand'
import { SimpleEncounter } from '@/Domain/Encounter/SimpleEncounter'
import { EncounterForPartyWriteModel } from '@/Domain/Encounter/EncounterForPartyWriteModel'

export class CreateEncounterForPartyCommandHandler {
  // eslint-disable-next-line
  constructor(private readonly writeModel: EncounterForPartyWriteModel) {}

  async handle (command: CreateEncounterForPartyCommand): Promise<void> {
    const partyUlid = Ulid.fromString(command.partyUlid)
    const encounter = SimpleEncounter.fromName(command.encounterName)
    await this.writeModel.createForParty(partyUlid, encounter)
  }
}
