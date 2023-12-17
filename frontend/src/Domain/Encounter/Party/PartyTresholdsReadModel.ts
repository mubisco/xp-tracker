import { PartyTresholdDto } from './PartyTresholdDto'

export interface PartyTresholdsReadModel {
  fetchTresholds(): Promise<PartyTresholdDto>
}
