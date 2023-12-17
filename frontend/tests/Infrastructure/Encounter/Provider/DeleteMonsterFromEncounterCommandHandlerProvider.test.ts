import { DeleteMonsterFromEncounterCommandHandler } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommandHandler'
import { DeleteMonsterFromEncounterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/DeleteMonsterFromEncounterCommandHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing DeleteMonsterFromEncounterCommandHandlerProvider', () => {
  test('It should be of proper class', () => {
    const sut = new DeleteMonsterFromEncounterCommandHandlerProvider()
    expect(sut).toBeInstanceOf(DeleteMonsterFromEncounterCommandHandlerProvider)
  })
  test('It should return proper instance', () => {
    const sut = new DeleteMonsterFromEncounterCommandHandlerProvider()
    const handler = sut.provide()
    expect(handler).toBeInstanceOf(DeleteMonsterFromEncounterCommandHandler)
  })
})
