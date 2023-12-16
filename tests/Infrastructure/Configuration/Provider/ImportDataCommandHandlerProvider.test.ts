import { describe, expect, test } from 'vitest'
import { ImportDataCommandHandlerProvider } from './ImportDataCommandHandlerProvider'
import { ImportDataCommandHandler } from '@/Application/Configuration/Command/ImportDataCommandHandler'

describe('Testing ExportDataQueryHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new ImportDataCommandHandlerProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(ImportDataCommandHandler)
  })
})
