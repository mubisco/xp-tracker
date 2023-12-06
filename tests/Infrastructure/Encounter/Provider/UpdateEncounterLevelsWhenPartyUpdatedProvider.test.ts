import { UpdateEncounterLevelsWhenPartyUpdatedEventHandler } from '@/Application/Encounter/Event/UpdateEncounterLevelsWhenPartyUpdated'
import { UpdateEncounterLevelsWhenPartyUpdatedProvider } from '@/Infrastructure/Encounter/Provider/UpdateEncounterLevelsWhenPartyUpdatedProvider'
import { describe, expect, test } from 'vitest'

describe('Testing UpdateEncounterLevelsWhenPartyUpdatedProvider', () => {
  test('It should be of proper class', () => {
    const sut = new UpdateEncounterLevelsWhenPartyUpdatedProvider()
    expect(sut).toBeInstanceOf(UpdateEncounterLevelsWhenPartyUpdatedProvider)
  })
  test('It should return proper instance', () => {
    const sut = new UpdateEncounterLevelsWhenPartyUpdatedProvider()
    const handler = sut.provide()
    expect(handler).toBeInstanceOf(UpdateEncounterLevelsWhenPartyUpdatedEventHandler)
  })
})
