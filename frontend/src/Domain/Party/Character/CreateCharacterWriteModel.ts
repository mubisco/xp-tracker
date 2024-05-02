import { Character } from './Character'

export interface CreateCharacterWriteModel {
  createCharacter(character: Character): Promise<void>;
}
