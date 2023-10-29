import { PartyTresholdDto } from '@/Domain/Encounter/Party/PartyTresholdDto'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'
import { LocalStorageTag } from '@/Infrastructure/Persistence/LocalStorageTag'

const LOCALSTORAGE_TAG = LocalStorageTag.CHARACTERS

interface CharacterRawData { [ key: string ]: string }

export class LocalStoragePartyTresholdsRepository implements PartyTresholdsReadModel {
  private _localStorageRawCharacters: CharacterRawData = {}

  constructor () {
    this.readLocalStorageContent()
  }

  async fetchTresholds (): Promise<PartyTresholdDto> {
    const levels: number[] = []
    for (const characterId in this._localStorageRawCharacters) {
      const characterData = JSON.parse(this._localStorageRawCharacters[characterId])
      const level = parseInt(characterData.level, 10)
      levels.push(level)
    }
    const dto = new PartyTresholdDto(levels)
    return Promise.resolve(dto)
  }

  private readLocalStorageContent (): void {
    const result = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    this._localStorageRawCharacters = JSON.parse(result)
  }
}
