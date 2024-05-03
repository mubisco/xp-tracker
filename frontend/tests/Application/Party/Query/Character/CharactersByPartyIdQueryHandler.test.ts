import { CharactersByPartyIdQuery } from '@/Application/Party/Query/Character/CharactersByPartyIdQuery'
import { CharactersByPartyIdQueryHandler } from '@/Application/Party/Query/Character/CharactersByPartyIdQueryHandler'
import { SimpleCharacterReadModelStub } from '@tests/Domain/Party/Character/SimpleCharacterReadModelStub'

import { describe, expect, test } from 'vitest'

describe('Testing AllPartiesQueryHandler', () => {
  test('It should throw error when party ulid has wrong format', () => {
    const sut = new CharactersByPartyIdQueryHandler(new SimpleCharacterReadModelStub())
    const query = new CharactersByPartyIdQuery('1HWZ1QBZ8KFBG7BQ9M1Z32ZGY')
    expect(sut.handle(query)).rejects.toThrow(RangeError)
  })
  test('It should return proper data', async () => {
    const sut = new CharactersByPartyIdQueryHandler(new SimpleCharacterReadModelStub())
    const query = new CharactersByPartyIdQuery('01HWZ1QBZ8KFBG7BQ9M1Z32ZGY')
    const result = await sut.handle(query)
    expect(result).toHaveLength(1)
  })
})
