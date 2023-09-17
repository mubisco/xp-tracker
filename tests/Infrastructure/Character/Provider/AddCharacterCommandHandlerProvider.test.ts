import { AddCharacterCommandHandler } from '@/Application/Character/Command/AddCharacterCommandHandler'
import { AddCharacterCommandHandlerProvider } from '@/Infrastructure/Character/Provider/AddCharacterCommandHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing AddCharacterCommandHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new AddCharacterCommandHandlerProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(AddCharacterCommandHandler)
  })
})
