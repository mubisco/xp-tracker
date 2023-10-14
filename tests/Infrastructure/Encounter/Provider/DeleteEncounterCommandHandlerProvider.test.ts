import { describe, expect, test } from 'vitest'
import { DeleteEncounterCommandHandler } from '@/Application/Encounter/Command/DeleteEncounterCommandHandler'
import { DeleteEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/DeleteEncounterCommandHandlerProvider'

describe('Testing DeleteEncounterCommandHandlerProvider', () => {
  test('It should be of proper class', () => {
    const sut = new DeleteEncounterCommandHandlerProvider()
    expect(sut).toBeInstanceOf(DeleteEncounterCommandHandlerProvider)
  })
  test('It should return proper instance', () => {
    const sut = new DeleteEncounterCommandHandlerProvider()
    const handler = sut.provide()
    expect(handler).toBeInstanceOf(DeleteEncounterCommandHandler)
  })
})
