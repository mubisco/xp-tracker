import { PartyTresholdDto } from '@/Domain/Encounter/Party/PartyTresholdDto'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'

export class PartyTresholdReadModelDummy implements PartyTresholdsReadModel {
  async fetchTresholds (): Promise<PartyTresholdDto> {
    const dto = new PartyTresholdDto(5, 50000)
    return Promise.resolve(dto)
  }
}
