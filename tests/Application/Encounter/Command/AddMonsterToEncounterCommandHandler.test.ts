import { AddMonsterToEncounterCommandHandler } from '@/Application/Encounter/Command/AddMonsterToEncounterCommandHandler'
import { describe, expect, test } from 'vitest'

describe('Testing AddMonsterToEncounterCommandHandler', () => {
  test('it should be of proper class', () => {
    const sut = new AddMonsterToEncounterCommandHandler()
    expect(sut).toBeInstanceOf(AddMonsterToEncounterCommandHandler)
  })
  test('It should throw error when name not valid', () => {
    const sut = new AddMonsterToEncounterCommandHandler()
    expect(sut.handle()).rejects.toThrow(Error)
  })
})
