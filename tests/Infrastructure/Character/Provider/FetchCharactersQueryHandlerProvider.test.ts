import { FetchCharactersQueryHandler } from '@/Application/Character/Query/FetchCharactersQueryHandler'
import { FetchCharactersQueryHandlerProvider } from '@/Infrastructure/Character/Provider/FetchCharactersQueryHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing AddCharacterCommandHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new FetchCharactersQueryHandlerProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(FetchCharactersQueryHandler)
  })
})
