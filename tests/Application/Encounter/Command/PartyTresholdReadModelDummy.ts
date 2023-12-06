import { PartyTresholdDto } from '@/Domain/Encounter/Party/PartyTresholdDto'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'

export class PartyTresholdReadModelDummy implements PartyTresholdsReadModel {
  async fetchTresholds (): Promise<PartyTresholdDto> {
    const dto = new PartyTresholdDto([8, 8, 8])
    return Promise.resolve(dto)
  }
}
