import { Character } from '@/Domain/Party/Character/Character'
import { CharacterWriteModelError } from '@/Domain/Party/Character/CharacterWriteModelError'
import { CreateCharacterWriteModel } from '@/Domain/Party/Character/CreateCharacterWriteModel'

export class FetchCharacterClient implements CreateCharacterWriteModel {
  // eslint-disable-next-line
  constructor(private readonly baseUrl: string) {}

  async createCharacter (character: Character): Promise<void> {
    const data = {
      ulid: character.ulid(),
      characterName: character.name(),
      experiencePoints: character.xp()
    }
    const url = this.baseUrl + '/character'
    const response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    if (!response.ok) {
      const content = await response.text()
      throw new CharacterWriteModelError(content)
    }
  }
}
