import { Experience } from '@/Domain/Character/Experience'
import { PartyRepository } from '@/Domain/Character/Party/PartyRepository'
import { UpdateExperiencePartyWriteModel } from '@/Domain/Character/Party/UpdateExperiencePartyWriteModel'
import { EncounterWasFinished } from '@/Domain/Encounter/EncounterWasFinished'
import { EventBus } from '@/Domain/Shared/Event/EventBus'
import { EventListener } from '@/Domain/Shared/Event/EventListener'

export class UpdatePartyExperienceWhenEncounterWasFinishedEventHandler implements EventListener {
  // eslint-disable-next-line
  constructor (
    private readonly partyRepository: PartyRepository,
    private readonly writeModel: UpdateExperiencePartyWriteModel,
    private readonly eventBus: EventBus
  ) {}

  listensTo (eventName: string): boolean {
    return eventName === 'EncounterWasFinished'
  }

  async handle (event: EncounterWasFinished): Promise<void> {
    const experience = Experience.fromXp(event.payload().totalXp)
    const party = await this.partyRepository.find()
    party.updateExperience(experience)
    await this.writeModel.updateParty(party)
    this.eventBus.publish(party.pullEvents())
  }
}
