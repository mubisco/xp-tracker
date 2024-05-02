import { PartyDto } from './PartyDto'

export interface PartyReadModel {
  allParties(): Promise<PartyDto[]>;
}
