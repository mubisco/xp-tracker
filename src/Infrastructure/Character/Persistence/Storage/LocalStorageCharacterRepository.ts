import { Character } from '@/Domain/Character/Character'
import { CharacterRepository } from '@/Domain/Character/CharacterRepository'
import { CharacterRepositoryError } from '@/Domain/Character/CharacterRepositoryError'
import { CharacterVisitor } from '@/Domain/Character/CharacterVisitor'

const LOCALSTORAGE_TAG = 'characters'

export class LocalStorageCharacterRepository implements CharacterRepository {
  private _visitor: CharacterVisitor<string>

  constructor (visitor: CharacterVisitor<string>) {
    this._visitor = visitor
  }

  async store (character: Character): Promise<void> {
    const result = localStorage.getItem(LOCALSTORAGE_TAG) ?? '{}'
    const parsedResult = JSON.parse(result)
    const characterId = character.id().value()
    if (parsedResult[characterId]) {
      return Promise.reject(
        new CharacterRepositoryError(`Character with id ${characterId} already present on Storage`)
      )
    }
    const visitedCharacter = character.visit(this._visitor)
    parsedResult[characterId] = visitedCharacter
    window.localStorage.setItem(LOCALSTORAGE_TAG, JSON.stringify(parsedResult))
    return Promise.resolve()
  }
}
