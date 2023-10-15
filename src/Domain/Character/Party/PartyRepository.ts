import { Party } from './Party'

export interface PartyRepository {
  find (): Promise<Party>
}
