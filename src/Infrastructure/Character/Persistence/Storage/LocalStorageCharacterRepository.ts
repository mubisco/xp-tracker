import { Character } from '@/Domain/Character/Character'
import { CharacterVisitor } from '@/Domain/Character/CharacterVisitor'
import { CharacterRepositoryError } from './CharacterRepositoryError'

const LOCALSTORAGE_TAG = 'characters'

export class LocalStorageCharacterRepository {
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
