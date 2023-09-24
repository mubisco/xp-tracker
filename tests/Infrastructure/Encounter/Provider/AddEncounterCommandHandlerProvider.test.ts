import { AddEncounterCommandHandler } from '@/Application/Encounter/Command/AddEncounterCommandHandler'
import { AddEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/AddEncounterCommandHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing AddEncounterCommandHandlerProvider', () => {
  test('It should be of proper class', () => {
    const sut = new AddEncounterCommandHandlerProvider()
    expect(sut).toBeInstanceOf(AddEncounterCommandHandlerProvider)
  })
  test('It should return proper instance', () => {
    const sut = new AddEncounterCommandHandlerProvider()
    const handler = sut.provide()
    expect(handler).toBeInstanceOf(AddEncounterCommandHandler)
  })
})
