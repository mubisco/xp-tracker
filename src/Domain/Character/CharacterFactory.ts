import { Character } from './Character'

export interface CharacterFactory {
  make (data: { [key: string]: any }): Character
}
