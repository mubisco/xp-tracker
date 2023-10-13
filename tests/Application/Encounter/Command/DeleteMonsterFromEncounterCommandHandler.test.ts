import { DeleteMonsterFromEncounterCommand } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommand'
import { DeleteMonsterFromEncounterCommandHandler } from '@/Application/Encounter/Command/DeleteMonsterFromEncounterCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { MonsterNotFoundError } from '@/Domain/Encounter/MonsterNotFoundError'
import { beforeEach, describe, expect, test } from 'vitest'
import { NotFoundEncounterRepositoryDummy } from './NotFoundEncounterRepositoryDummy'
import { EncounterRepositoryDummy } from './EncounterRepositoryDummy'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { UpdateEncounterWriteModelSpy } from './UpdateEncounterWriteModelSpy'
import { FailingUpdateEncounterWriteModel } from './FailingUpdateEncounterWriteModel'

describe('Testing DeleteMonsterFromEncounterCommandHandler', () => {
  let sut: DeleteMonsterFromEncounterCommandHandler
  const command = new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0T', 'Some name', 2500, '1/2')

  beforeEach(() => {
    sut = new DeleteMonsterFromEncounterCommandHandler(new NotFoundEncounterRepositoryDummy(), new FailingUpdateEncounterWriteModel())
  })
  test('It should throw error when encounter id is wrong', () => {
    const wrongEncounterUlidCommand = new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0', 'Orco', 500, '1/2')
    expect(sut.handle(wrongEncounterUlidCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when monster data is wrong', () => {
    const wrongEncounterUlidCommand = new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0T', 'Orco', 0, '1/2')
    expect(sut.handle(wrongEncounterUlidCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter not found', () => {
    expect(sut.handle(command)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should throw error when monster not found on encounter', () => {
    sut = new DeleteMonsterFromEncounterCommandHandler(new EncounterRepositoryDummy(), new FailingUpdateEncounterWriteModel())
    const notExistingMonsterCommand = new DeleteMonsterFromEncounterCommand('01HCM7C58CY0CAW9F8WV5QCM0T', 'Orco 2', 500, '1/2')
    expect(sut.handle(notExistingMonsterCommand)).rejects.toThrow(MonsterNotFoundError)
  })
  test('It should throw error when encounter cannot be updated', () => {
    sut = new DeleteMonsterFromEncounterCommandHandler(new EncounterRepositoryDummy(), new FailingUpdateEncounterWriteModel())
    expect(sut.handle(command)).rejects.toThrow(EncounterWriteModelError)
  })
  test('It should update encounter properly', async () => {
    const spy = new UpdateEncounterWriteModelSpy()
    sut = new DeleteMonsterFromEncounterCommandHandler(new EncounterRepositoryDummy(), spy)
    await sut.handle(command)
    const encounter = spy.getUpdatedEncounter()
    expect(encounter.monsters()).toHaveLength(0)
  })
})
