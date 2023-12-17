import { DeleteEncounterCommand } from '@/Application/Encounter/Command/DeleteEncounterCommand'
import { DeleteEncounterCommandHandler } from '@/Application/Encounter/Command/DeleteEncounterCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { NotFoundEncounterRepositoryDummy } from './NotFoundEncounterRepositoryDummy'
import { EncounterRepositoryDummy } from './EncounterRepositoryDummy'
import { DeleteEncounterWriteModelSpy } from './DeleteEncounterWriteModelSpy'
import { FailingDeleteEncounterWriteModel } from './FailingDeleteEncounterWriteModel'

describe('Testing DeleteEncounterCommandHandler', () => {
  let notFoundRepository: NotFoundEncounterRepositoryDummy
  let dummyRepository: EncounterRepositoryDummy
  let failingWriteModel: FailingDeleteEncounterWriteModel
  let writeModelSpy: DeleteEncounterWriteModelSpy
  beforeEach(() => {
    notFoundRepository = new NotFoundEncounterRepositoryDummy()
    dummyRepository = new EncounterRepositoryDummy()
    failingWriteModel = new FailingDeleteEncounterWriteModel()
    writeModelSpy = new DeleteEncounterWriteModelSpy()
  })
  test('It should throw error when encounterId has wrong format', () => {
    const sut = new DeleteEncounterCommandHandler(notFoundRepository, failingWriteModel)
    const wrongCommand = new DeleteEncounterCommand('1HB4CRC4FN1V6TVW2DYHMA68F')
    expect(sut.handle(wrongCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter not found', () => {
    const sut = new DeleteEncounterCommandHandler(notFoundRepository, failingWriteModel)
    const command = new DeleteEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F')
    expect(sut.handle(command)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should throw error when encounter cannot be deleted', () => {
    const sut = new DeleteEncounterCommandHandler(dummyRepository, failingWriteModel)
    const command = new DeleteEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F')
    expect(sut.handle(command)).rejects.toThrow(EncounterWriteModelError)
  })
  test('It should delete encounter properly', async () => {
    const sut = new DeleteEncounterCommandHandler(dummyRepository, writeModelSpy)
    const command = new DeleteEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F')
    await sut.handle(command)
    expect(writeModelSpy.called).toBe(1)
  })
})
