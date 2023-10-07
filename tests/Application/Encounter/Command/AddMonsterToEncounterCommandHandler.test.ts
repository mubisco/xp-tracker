import { AddMonsterToEncounterCommand } from '@/Application/Encounter/Command/AddMonsterToEncounterCommand'
import { AddMonsterToEncounterCommandHandler } from '@/Application/Encounter/Command/AddMonsterToEncounterCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { describe, expect, test } from 'vitest'
import { NotFoundEncounterRepositoryDummy } from './NotFoundEncounterRepositoryDummy'
import { EncounterRepositoryDummy } from './EncounterRepositoryDummy'
import { FailingUpdateEncounterWriteModel } from './FailingUpdateEncounterWriteModel'
import { UpdateEncounterWriteModelSpy } from './UpdateEncounterWriteModelSpy'

const notFoundRepositoryDummy = new NotFoundEncounterRepositoryDummy()
const repositoryDummy = new EncounterRepositoryDummy()
const failingUpdateEncounterWriteModel = new FailingUpdateEncounterWriteModel()
const updateEncounterWriteModelSpy = new UpdateEncounterWriteModelSpy()

describe('Testing AddMonsterToEncounterCommandHandler', () => {
  test('it should be of proper class', () => {
    const sut = new AddMonsterToEncounterCommandHandler(notFoundRepositoryDummy, failingUpdateEncounterWriteModel)
    expect(sut).toBeInstanceOf(AddMonsterToEncounterCommandHandler)
  })
  test('It should throw error when ulid not valid', () => {
    const sut = new AddMonsterToEncounterCommandHandler(notFoundRepositoryDummy, failingUpdateEncounterWriteModel)
    const wrongUlidCommand = new AddMonsterToEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA6F', 'Pollo primero', 2500, '1/2')
    expect(sut.handle(wrongUlidCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when name not valid', () => {
    const sut = new AddMonsterToEncounterCommandHandler(notFoundRepositoryDummy, failingUpdateEncounterWriteModel)
    const wrongNameCommand = new AddMonsterToEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F', '', 2500, '1/2')
    expect(sut.handle(wrongNameCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when experiencePoints not valid', () => {
    const sut = new AddMonsterToEncounterCommandHandler(notFoundRepositoryDummy, failingUpdateEncounterWriteModel)
    const wrongExperienceCommmand = new AddMonsterToEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F', 'Pollo Primero', 0, '1/2')
    expect(sut.handle(wrongExperienceCommmand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when challenge rating not valid', () => {
    const sut = new AddMonsterToEncounterCommandHandler(notFoundRepositoryDummy, failingUpdateEncounterWriteModel)
    const wrongCrCommmand = new AddMonsterToEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F', 'Pollo Primero', 2500, '1/3')
    expect(sut.handle(wrongCrCommmand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when monster not found', () => {
    const sut = new AddMonsterToEncounterCommandHandler(notFoundRepositoryDummy, failingUpdateEncounterWriteModel)
    const command = new AddMonsterToEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F', 'Pollo primero', 2500, '1/2')
    expect(sut.handle(command)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should throw error when encounter not updated', () => {
    const sut = new AddMonsterToEncounterCommandHandler(repositoryDummy, failingUpdateEncounterWriteModel)
    const command = new AddMonsterToEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F', 'Pollo primero', 2500, '1/2')
    expect(sut.handle(command)).rejects.toThrow(EncounterWriteModelError)
  })
  test('It should add encounter with monster', async () => {
    const sut = new AddMonsterToEncounterCommandHandler(repositoryDummy, updateEncounterWriteModelSpy)
    const command = new AddMonsterToEncounterCommand('01HB4CRC4FN1V6TVW2DYHMA68F', 'Pollo primero', 2500, '1/2')
    await sut.handle(command)
    const encounter = updateEncounterWriteModelSpy.getUpdatedEncounter()
    const monsters = encounter.monsters()
    const monster = monsters[0]
    expect(monster.name()).toBe('Pollo primero')
  })
})
