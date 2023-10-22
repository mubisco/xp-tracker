import { FinishEncounterCommandHandler } from '@/Application/Encounter/Command/FinishEncounterCommandHandler'
import { FinishEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/FinishEncounterCommandHandlerProvider'
import { InMemoryEventBusSpy } from '@tests/Application/Encounter/Command/InMemoryEventBusSpy'
import { describe, expect, test } from 'vitest'

describe('Testing FinishEncounterCommandHandlerProvider', () => {
  test('It should be of proper class', () => {
    const sut = new FinishEncounterCommandHandlerProvider()
    expect(sut).toBeInstanceOf(FinishEncounterCommandHandlerProvider)
  })
  test('It should return proper instance', () => {
    const sut = new FinishEncounterCommandHandlerProvider()
    const eventBus = new InMemoryEventBusSpy()
    const handler = sut.provide(eventBus)
    expect(handler).toBeInstanceOf(FinishEncounterCommandHandler)
  })
})
