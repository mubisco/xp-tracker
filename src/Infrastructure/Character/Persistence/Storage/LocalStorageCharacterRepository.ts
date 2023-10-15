import { Character } from '@/Domain/Character/Character'
import { CharacterVisitor } from '@/Domain/Character/CharacterVisitor'
import { AddCharacterWriteModel } from '@/Domain/Character/AddCharacterWriteModel'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { CharacterListReadModel } from '@/Domain/Character/CharacterListReadModel'
import { CharacterDto } from '@/Domain/Character/CharacterDto'
import { DeleteCharacterWriteModel } from '@/Domain/Character/DeleteCharacterWriteModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { CharacterNotFoundError } from '@/Domain/Character/CharacterNotFoundError'
import { PartyRepository } from '@/Domain/Character/Party/PartyRepository'
import { Party } from '@/Domain/Character/Party/Party'
import { CharacterParty } from '@/Domain/Character/Party/CharacterParty'
import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { Experience } from '@/Domain/Character/Experience'
import { Health } from '@/Domain/Character/Health'
import { UpdateExperiencePartyWriteModel } from '@/Domain/Character/Party/UpdateExperiencePartyWriteModel'

const LOCALSTORAGE_TAG = 'characters'

interface CharacterRawData { [ key: string ]: string }

export class LocalStorageCharacterRepository implements
AddCharacterWriteModel,
CharacterListReadModel,
DeleteCharacterWriteModel,
PartyRepository,
UpdateExperiencePartyWriteModel {
  private _localStorageRawCharacters: CharacterRawData = {}

  constructor (private readonly visitor: CharacterVisitor<string>) {
    this.readLocalStorageContent()
  }

  async updateParty (party: Party): Promise<void> {
    const characterParty = party as CharacterParty
    // @ts-ignore
    const characters = characterParty.members
    characters.forEach(async (character: Character): Promise<void> => {
      await this.update(character)
    })
    return Promise.resolve()
  }

  async update (character: Character): Promise<void> {
    const characterId = character.id().value()
    if (!this.characterIdExists(character.id())) {
      return Promise.reject(
        new CharacterWriteModelError(`Character with id ${characterId} already present on Storage`)
      )
    }
    const visitedCharacter = character.visit(this.visitor)
    this._localStorageRawCharacters[characterId] = visitedCharacter
    this.updateLocalStorage()
    return Promise.resolve()
  }

  async find (): Promise<Party> {
    const charactersDto = await this.read()
    const characters = charactersDto.map((dto: CharacterDto): Character => {
      const character = BasicCharacter.fromValues(
        CharacterName.fromString(dto.name),
        Experience.fromXp(dto.xp),
        Health.fromValues(dto.maxHp, dto.currentHp)
      )
      // @ts-ignore
      character._characterId = Ulid.fromString(dto.ulid)
      return character
    })
    return new CharacterParty(characters)
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

  async remove (characterUlid: Ulid): Promise<void> {
    if (!this.characterIdExists(characterUlid)) {
      return Promise.reject(new CharacterNotFoundError())
    }
    this.removeCharacter(characterUlid)
    this.updateLocalStorage()
    return Promise.resolve()
  }

  async read (): Promise<CharacterDto[]> {
    this.readLocalStorageContent()
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
