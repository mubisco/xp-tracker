import { CharacterRepository } from '@/Domain/Character/CharacterRepository'
import { Character } from '@/Domain/Character/Character'

export class MockedCharacterRepository implements CharacterRepository {
  public character: Character | null = null

  async store (character: Character): Promise<void> {
    this.character = character
  }
}
