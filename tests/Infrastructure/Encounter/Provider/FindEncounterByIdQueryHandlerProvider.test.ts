import { FindEncounterByIdQueryHandler } from '@/Application/Encounter/Query/FindEncounterByIdQueryHandler'
import { FindEncounterByIdQueryHandlerProvider } from '@/Infrastructure/Encounter/Provider/FindEncounterByIdQueryHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing FindEncounterByIdQueryHandlerProvider', () => {
  test('It should be of proper class', () => {
    const sut = new FindEncounterByIdQueryHandlerProvider()
    expect(sut).toBeInstanceOf(FindEncounterByIdQueryHandlerProvider)
  })
  test('It should return proper instance', () => {
    const sut = new FindEncounterByIdQueryHandlerProvider()
    const handler = sut.provide()
    expect(handler).toBeInstanceOf(FindEncounterByIdQueryHandler)
  })
})
