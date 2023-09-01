import { Character } from './Character'

export interface CharacterRepository {
  store (character: Character): Promise<void>
}
