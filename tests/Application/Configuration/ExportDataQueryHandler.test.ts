import { ExportDataQuery } from '@/Application/Configuration/Query/ExportDataQuery'
import { ExportDataQueryHandler } from '@/Application/Configuration/Query/ExportDataQueryHandler'
import { ConfigurationReadModelError } from '@/Domain/Configuration/ConfigurationReadModelError'
import { describe, expect, test } from 'vitest'
import { FailingConfigurationReadModelDummy } from './FailingConfigurationReadModeDummy'
import { ConfigurationReadModelStub } from './ConfigurationReadModelStub'

describe('Testing ExportDataQueryHandler', () => {
  const failingReadModel = new FailingConfigurationReadModelDummy()
  const stubReadModel = new ConfigurationReadModelStub()

  test('It should be of proper class', () => {
    const sut = new ExportDataQueryHandler(failingReadModel)
    expect(sut).toBeInstanceOf(ExportDataQueryHandler)
  })
  test('It should throw exception when filename provided is not valid', () => {
    const sut = new ExportDataQueryHandler(failingReadModel)
    const query = new ExportDataQuery('as')
    expect(sut.handle(query)).rejects.toThrow(RangeError)
  })
  test('It should throw exception when configuration cannot be read', () => {
    const sut = new ExportDataQueryHandler(failingReadModel)
    const query = new ExportDataQuery('valid_filename8')
    expect(sut.handle(query)).rejects.toThrow(ConfigurationReadModelError)
  })
  test('It should return proper configuration', async () => {
    const sut = new ExportDataQueryHandler(stubReadModel)
    const query = new ExportDataQuery('valid_filename8')
    const result = await sut.handle(query)
    expect(result).toStrictEqual({ 'valid_filename8.json': 'asdasdasd' })
  })
})
