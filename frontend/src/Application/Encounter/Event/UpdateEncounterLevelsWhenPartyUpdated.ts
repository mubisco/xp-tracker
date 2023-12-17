import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'
import { EventListener } from '@/Domain/Shared/Event/EventListener'

export class UpdateEncounterLevelsWhenPartyUpdatedEventHandler implements EventListener {
  // eslint-disable-next-line
  constructor (
    private readonly partyTresholdReadModel: PartyTresholdsReadModel,
    private readonly encounterRepository: EncounterRepository,
    private readonly updateEncounterWriteModel: UpdateEncounterWriteModel
  ) {}

  listensTo (eventName: string): boolean {
    return eventName === 'PartyWasUpdated'
  }

  // eslint-disable-next-line
  async handle (event: PartyWasUpdated): Promise<void> {
    const tresholds = await this.partyTresholdReadModel.fetchTresholds()
    const encounters = await this.encounterRepository.allEncounters()
    for await (const encounter of encounters) {
      encounter.updateLevel(tresholds)
      await this.updateEncounterWriteModel.update(encounter)
    }
  }
}
