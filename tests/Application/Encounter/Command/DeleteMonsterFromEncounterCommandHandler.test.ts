import { DeleteMonsterFromEncounterCommand } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommand'
import { DeleteMonsterFromEncounterCommandHandler } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { describe, expect, test } from 'vitest'

describe('Testing DeleteMonsterFromEncounterCommandHandler', () => {
  test('It should be of proper class', () => {
    const sut = new DeleteMonsterFromEncounterCommandHandler()
    expect(sut).toBeInstanceOf(DeleteMonsterFromEncounterCommandHandler)
  })
  test('It should throw error when encounter id is wrong', () => {
    const sut = new DeleteMonsterFromEncounterCommandHandler()
    expect(sut.handle(new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0'))).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter not found', () => {
    const sut = new DeleteMonsterFromEncounterCommandHandler()
    expect(sut.handle(new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0T'))).rejects.toThrow(EncounterNotFoundError)
  })
})
