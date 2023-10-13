import { AddMonsterToEncounterCommandHandler } from '@/Application/Encounter/Command/AddMonsterToEncounterCommandHandler'
import { AddMonsterCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/AddMonsterCommandHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing AddMonsterCommandHandlerProvider', () => {
  test('It should be of proper class', () => {
    const sut = new AddMonsterCommandHandlerProvider()
    expect(sut).toBeInstanceOf(AddMonsterCommandHandlerProvider)
  })
  test('It should return proper instance', () => {
    const sut = new AddMonsterCommandHandlerProvider()
    const handler = sut.provide()
    expect(handler).toBeInstanceOf(AddMonsterToEncounterCommandHandler)
  })
})
