import { Character } from '@/Domain/Character/Character'
import { CharacterVisitor } from '@/Domain/Character/CharacterVisitor'
import { AddCharacterWriteModel } from '@/Domain/Character/AddCharacterWriteModel'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { CharacterListReadModel } from '@/Domain/Character/CharacterListReadModel'
import { CharacterDto } from '@/Domain/Character/CharacterDto'
import { DeleteCharacterWriteModel } from '@/Domain/Character/DeleteCharacterWriteModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { CharacterNotFoundError } from '@/Domain/Character/CharacterNotFoundError'

const LOCALSTORAGE_TAG = 'characters'

interface CharacterRawData { [ key: string ]: string }

export class LocalStorageCharacterRepository implements AddCharacterWriteModel, CharacterListReadModel, DeleteCharacterWriteModel {
  private _localStorageRawCharacters: CharacterRawData = {}

  constructor (private readonly visitor: CharacterVisitor<string>) {
    this.readLocalStorageContent()
  }

  async invoke (character: Character): Promise<void> {
    const characterId = character.id().value()
    if (this.characterIdExists(character.id())) {
      return Promise.reject(
        new CharacterWriteModelError(`Character with id ${characterId} already present on Storage`)
      )
    }
    const visitedCharacter = character.visit(this.visitor)
    this._localStorageRawCharacters[characterId] = visitedCharacter
    this.updateLocalStorage()
    return Promise.resolve()
  }

  async byUlid (characterUlid: Ulid): Promise<void> {
    this.readLocalStorageContent()
    if (!this.characterIdExists(characterUlid)) {
      return Promise.reject(new CharacterNotFoundError())
    }
    this.removeCharacter(characterUlid)
    this.updateLocalStorage()
  }

  async read (): Promise<CharacterDto[]> {
    const result = []
    for (const characterId in this._localStorageRawCharacters) {
      const parsedDto = this.fromRawData(this._localStorageRawCharacters[characterId])
      result.push(parsedDto)
    }
    return result
  }

  private removeCharacter (characterUlid: Ulid): void {
    const filteredItems: CharacterRawData = {}
    for (const characterId in this._localStorageRawCharacters) {
      if (characterId !== characterUlid.value()) {
        filteredItems[characterId] = this._localStorageRawCharacters[characterId]
      }
    }
    this._localStorageRawCharacters = filteredItems
  }

  private updateLocalStorage (): void {
    window.localStorage.setItem(LOCALSTORAGE_TAG, JSON.stringify(this._localStorageRawCharacters))
  }

  private readLocalStorageContent (): void {
    const result = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    this._localStorageRawCharacters = JSON.parse(result)
  }

  private characterIdExists (characterUlid: Ulid): boolean {
    const characterKeys = Object.keys(this._localStorageRawCharacters)
    return characterKeys.indexOf(characterUlid.value()) > -1
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
}
