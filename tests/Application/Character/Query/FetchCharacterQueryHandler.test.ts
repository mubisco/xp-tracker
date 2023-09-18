import { FetchCharactersQuery } from '@/Application/Character/Query/FetchCharactersQuery'
import { FetchCharactersQueryHandler } from '@/Application/Character/Query/FetchCharactersQueryHandler'
import { CharacterReadModelError } from '@/Domain/Character/CharacterReadModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { CharacterListReadModelDummy } from './CharacterListReadModelDummy'

const query = new FetchCharactersQuery()
let sut: FetchCharactersQueryHandler
let readModel: CharacterListReadModelDummy

describe('Testing FetchCharactersQueryHandler', () => {
  beforeEach(() => {
    readModel = new CharacterListReadModelDummy()
    sut = new FetchCharactersQueryHandler(readModel)
  })
  test('It should be of proper class', () => {
    expect(sut).toBeInstanceOf(FetchCharactersQueryHandler)
  })
  test('It should throw error', () => {
    readModel.shouldFail = true
    expect(sut.invoke(query)).rejects.toThrow(CharacterReadModelError)
  })
  test('It should return proper list', async () => {
    const result = await sut.invoke(query)
    expect(result).toStrictEqual([
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
  })
})
