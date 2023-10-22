import { UpdatePartyExperienceWhenEncounterWasFinishedEventHandler } from '@/Application/Character/Event/UpdatePartyExperienceWhenEncounterWasFinishedEventHandler'
import { UpdatePartyExperienceWhenEncounterWasFinishedProvider } from '@/Infrastructure/Character/Provider/UpdatePartyExperienceWhenEncounterWasFinishedProvider'
import { describe, expect, test } from 'vitest'

describe('Testing UpdatePartyExperienceWhenEncounterWasFinishedProvider', () => {
  test('It should return proper handler', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(UpdatePartyExperienceWhenEncounterWasFinishedEventHandler)
  })
})
