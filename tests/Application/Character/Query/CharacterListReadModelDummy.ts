import { CharacterDto } from '@/Domain/Character/CharacterDto'
import { CharacterListReadModel } from '@/Domain/Character/CharacterListReadModel'
import { CharacterReadModelError } from '@/Domain/Character/CharacterReadModelError'

export class CharacterListReadModelDummy implements CharacterListReadModel {
  public shouldFail = false

  async read (): Promise<CharacterDto[]> {
    if (this.shouldFail) {
      return Promise.reject(new CharacterReadModelError(''))
    }
    return Promise.resolve([
      {
        ulid: 'ulid',
        name: 'name',
        currentHp: 25,
        maxHp: 25,
        xp: 325,
        nextLevel: 1000,
        level: 1
      }
    ])
  }
}
