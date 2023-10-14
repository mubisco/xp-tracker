import { Character } from '@/Domain/Character/Character'

export interface PartyRepository {
  allCharacters (): Promise<Character[]>
}
