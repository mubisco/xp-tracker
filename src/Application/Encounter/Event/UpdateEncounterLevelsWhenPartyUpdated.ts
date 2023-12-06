import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'
import { EncounterRepository } from '@/Domain/Encounter/EncounterRepository'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'
import { UpdateEncounterWriteModel } from '@/Domain/Encounter/UpdateEncounterWriteModel'

export class UpdateEncounterLevelsWhenPartyUpdated {
  // eslint-disable-next-line
  constructor (
    private readonly partyTresholdReadModel: PartyTresholdsReadModel,
    private readonly encounterRepository: EncounterRepository,
    private readonly updateEncounterWriteModel: UpdateEncounterWriteModel
  ) {}

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
