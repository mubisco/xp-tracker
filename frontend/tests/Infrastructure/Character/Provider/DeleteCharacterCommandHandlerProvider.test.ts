import { DeleteCharacterCommandHandler } from '@/Application/Character/Command/DeleteCharacterCommandHandler'
import { DeleteCharacterCommandHandlerProvider } from '@/Infrastructure/Character/Provider/DeleteCharacterCommandHandlerProvider'
import { InMemoryEventBusSpy } from '@tests/Application/Encounter/Command/InMemoryEventBusSpy'
import { describe, expect, test } from 'vitest'

describe('Testing AddCharacterCommandHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new DeleteCharacterCommandHandlerProvider()
    const eventBus = new InMemoryEventBusSpy()
    const result = sut.provide(eventBus)
    expect(result).toBeInstanceOf(DeleteCharacterCommandHandler)
  })
})
