import { CharacterRepository } from '@/Domain/Character/CharacterRepository'
import { Character } from '@/Domain/Character/Character'
import { CharacterRepositoryError } from '@/Domain/Character/CharacterRepositoryError'

export class FailingCharacterRepository implements CharacterRepository {
  // eslint-disable-next-line
  async store (character: Character): Promise<void> {
    return Promise.reject(new CharacterRepositoryError('asd'))
  }
}
