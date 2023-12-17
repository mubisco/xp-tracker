import { AddCharacterCommandHandler } from '@/Application/Character/Command/AddCharacterCommandHandler'
import { AddCharacterCommandHandlerProvider } from '@/Infrastructure/Character/Provider/AddCharacterCommandHandlerProvider'
import { InMemoryEventBusSpy } from '@tests/Application/Encounter/Command/InMemoryEventBusSpy'
import { describe, expect, test } from 'vitest'

describe('Testing AddCharacterCommandHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new AddCharacterCommandHandlerProvider()
    const eventBus = new InMemoryEventBusSpy()
    const result = sut.provide(eventBus)
    expect(result).toBeInstanceOf(AddCharacterCommandHandler)
  })
})
