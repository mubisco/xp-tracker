import { Experience } from '@/Domain/Character/Experience'
import { PartyRepository } from '@/Domain/Character/Party/PartyRepository'
import { UpdateExperiencePartyWriteModel } from '@/Domain/Character/Party/UpdateExperiencePartyWriteModel'
import { EncounterWasFinished } from '@/Domain/Encounter/EncounterWasFinished'

export class UpdatePartyExperienceWhenEncounterWasFinishedEventHandler {
  // eslint-disable-next-line
  constructor (
    private readonly partyRepository: PartyRepository,
    private readonly writeModel: UpdateExperiencePartyWriteModel
  ) {}

  async handle (event: EncounterWasFinished): Promise<void> {
    const experience = Experience.fromXp(event.payload().totalXp)
    const party = await this.partyRepository.find()
    party.updateExperience(experience)
    await this.writeModel.updateParty(party)
  }
}
