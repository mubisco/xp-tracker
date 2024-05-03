import { Character } from '@/Domain/Party/Character/Character'
import { CharacterWriteModelError } from '@/Domain/Party/Character/CharacterWriteModelError'
import { CreateCharacterWriteModel } from '@/Domain/Party/Character/CreateCharacterWriteModel'
import { SimpleCharacter } from '@/Domain/Party/Character/SimpleCharacter'
import { SimpleCharacterReadModel } from '@/Domain/Party/Character/SimpleCharacterReadModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class FetchCharacterClient implements CreateCharacterWriteModel, SimpleCharacterReadModel {
  // eslint-disable-next-line
  constructor(private readonly baseUrl: string) {}

  async ofPartyId (partyUlid: Ulid): Promise<SimpleCharacter[]> {
    const url = `${this.baseUrl}/party/${partyUlid.value()}/characters`
    const response = await fetch(url, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' }
    })
    return await response.json()
  }

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
