import { PartyTresholdDto } from '@/Domain/Encounter/Party/PartyTresholdDto'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'
import { PartyTresholdReadModelError } from '@/Domain/Encounter/Party/PartyTresholdsReadModelError'

export class FailingPartyTresholdReadModelDummy implements PartyTresholdsReadModel {
  async fetchTresholds (): Promise<PartyTresholdDto> {
    throw new PartyTresholdReadModelError('Method not implemented.')
  }
}
