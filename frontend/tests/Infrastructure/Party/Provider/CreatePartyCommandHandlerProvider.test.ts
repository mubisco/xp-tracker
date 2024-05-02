import { CreatePartyCommandHandler } from '@/Application/Party/Command/CreatePartyCommandHandler'
import { CreatePartyCommandHandlerProvider } from '@/Infrastructure/Party/Provider/CreatePartyCommandHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing AddCharacterCommandHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new CreatePartyCommandHandlerProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(CreatePartyCommandHandler)
  })
})
