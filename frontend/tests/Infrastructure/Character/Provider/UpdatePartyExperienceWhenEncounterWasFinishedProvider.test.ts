import { UpdatePartyExperienceWhenEncounterWasFinishedEventHandler } from '@/Application/Character/Event/UpdatePartyExperienceWhenEncounterWasFinishedEventHandler'
import { UpdatePartyExperienceWhenEncounterWasFinishedProvider } from '@/Infrastructure/Character/Provider/UpdatePartyExperienceWhenEncounterWasFinishedProvider'
import { InMemoryEventBusSpy } from '@tests/Application/Encounter/Command/InMemoryEventBusSpy'
import { describe, expect, test } from 'vitest'

describe('Testing UpdatePartyExperienceWhenEncounterWasFinishedProvider', () => {
  test('It should return proper handler', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedProvider()
    const eventBus = new InMemoryEventBusSpy()
    const result = sut.provide(eventBus)
    expect(result).toBeInstanceOf(UpdatePartyExperienceWhenEncounterWasFinishedEventHandler)
  })
})
