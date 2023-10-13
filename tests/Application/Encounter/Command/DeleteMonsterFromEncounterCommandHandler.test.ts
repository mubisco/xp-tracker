import { DeleteMonsterFromEncounterCommand } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommand'
import { DeleteMonsterFromEncounterCommandHandler } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { beforeEach, describe, expect, test } from 'vitest'

describe('Testing DeleteMonsterFromEncounterCommandHandler', () => {
  let sut: DeleteMonsterFromEncounterCommandHandler
  const command = new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0T')

  beforeEach(() => {
    sut = new DeleteMonsterFromEncounterCommandHandler()
  })
  test('It should throw error when encounter id is wrong', () => {
    const wrongEncounterUlidCommand = new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0')
    expect(sut.handle(wrongEncounterUlidCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter not found', () => {
    expect(sut.handle(command)).rejects.toThrow(EncounterNotFoundError)
  })
})
