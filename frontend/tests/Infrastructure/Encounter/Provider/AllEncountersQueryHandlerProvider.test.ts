import { AllEncountersQueryHandler } from '@/Application/Encounter/Query/AllEncountersQueryHandler'
import { AllEncountersQueryHandlerProvider } from '@/Infrastructure/Encounter/Provider/AllEncountersQueryHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing AddEncounterCommandHandlerProvider', () => {
  test('It should be of proper class', () => {
    const sut = new AllEncountersQueryHandlerProvider()
    expect(sut).toBeInstanceOf(AllEncountersQueryHandlerProvider)
  })
  test('It should return proper instance', () => {
    const sut = new AllEncountersQueryHandlerProvider()
    const handler = sut.provide()
    expect(handler).toBeInstanceOf(AllEncountersQueryHandler)
  })
})
