import { DeleteCharacterCommandHandler } from '@/Application/Character/Command/DeleteCharacterCommandHandler'
import { DeleteCharacterCommandHandlerProvider } from '@/Infrastructure/Character/Provider/DeleteCharacterCommandHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing AddCharacterCommandHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new DeleteCharacterCommandHandlerProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(DeleteCharacterCommandHandler)
  })
})

