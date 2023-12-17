import { ImportDataCommand } from '@/Application/Configuration/Command/ImportDataCommand'
import { ImportDataCommandHandler } from '@/Application/Configuration/Command/ImportDataCommandHandler'
import { ConfigurationWriteModelError } from '@/Domain/Configuration/ConfigurationWriteModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { FailingConfigurationWriteModel } from './FailingConfigurationWriteModel'
import { DummyConfigurationWriteModel } from './DummyConfigurationWriteModel'

const encodedContent = 'eyJjaGFyYWN0ZXJzIjp7fSwiZW5jb3VudGVycyI6e319'
let failingWriteModel: FailingConfigurationWriteModel
let dummyWriteModel: DummyConfigurationWriteModel

describe('Testing ImportDataCommandHandler', () => {
  beforeEach(() => {
    failingWriteModel = new FailingConfigurationWriteModel()
    dummyWriteModel = new DummyConfigurationWriteModel()
  })
  test('It should be of proper class', () => {
    const sut = new ImportDataCommandHandler(failingWriteModel)
    expect(sut).toBeInstanceOf(ImportDataCommandHandler)
  })
  test('It should throw error when no content', () => {
    const sut = new ImportDataCommandHandler(failingWriteModel)
    const wrongCommand = new ImportDataCommand('asd')
    expect(sut.handle(wrongCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when configuration cannot be updated', () => {
    const sut = new ImportDataCommandHandler(failingWriteModel)
    const command = new ImportDataCommand(encodedContent)
    expect(sut.handle(command)).rejects.toThrow(ConfigurationWriteModelError)
  })
  test('It should update config properly', async () => {
    const sut = new ImportDataCommandHandler(dummyWriteModel)
    const command = new ImportDataCommand(encodedContent)
    await sut.handle(command)
    expect(dummyWriteModel.updated).toBe(true)
  })
})
