import { Character } from '@/Domain/Character/Character'
import { CharacterVisitor } from '@/Domain/Character/CharacterVisitor'
import { AddCharacterWriteModel } from '@/Domain/Character/AddCharacterWriteModel'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { CharacterListReadModel } from '@/Domain/Character/CharacterListReadModel'
import { CharacterDto } from '@/Domain/Character/CharacterDto'

const LOCALSTORAGE_TAG = 'characters'

export class LocalStorageCharacterRepository implements AddCharacterWriteModel, CharacterListReadModel {
  private _visitor: CharacterVisitor<string>

  constructor (visitor: CharacterVisitor<string>) {
    this._visitor = visitor
  }

  async invoke (character: Character): Promise<void> {
    return this.store(character)
  }

  async read (): Promise<CharacterDto[]> {
    const data = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    const parsedData = JSON.parse(data)
    const result = []
    for (const characterId in parsedData) {
      result.push(this.fromRawData(parsedData[characterId]))
    }
    return result
  }

  private fromRawData (rawData: string): CharacterDto {
    const item = JSON.parse(rawData)
    return {
      ulid: item.id,
      name: item.name,
      maxHp: item.maxHitpoints,
      currentHp: item.currentHitpoints,
      xp: item.experiencePoints,
      level: item.level,
      nextLevel: item.nextLevel
    }
  }

  private async store (character: Character): Promise<void> {
    const result = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    const parsedResult = JSON.parse(result)
    const characterId = character.id().value()
    if (parsedResult[characterId]) {
      return Promise.reject(
        new CharacterWriteModelError(`Character with id ${characterId} already present on Storage`)
      )
    }
    const visitedCharacter = character.visit(this._visitor)
    parsedResult[characterId] = visitedCharacter
    window.localStorage.setItem(LOCALSTORAGE_TAG, JSON.stringify(parsedResult))
    return Promise.resolve()
  }
}
