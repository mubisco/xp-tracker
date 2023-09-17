import { Character } from './Character'

export interface AddCharacterWriteModel {
  invoke (character: Character): Promise<void>
}
