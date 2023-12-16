import { describe, expect, test } from 'vitest'
import { ImportDataCommandHandler } from '@/Application/Configuration/Command/ImportDataCommandHandler'
import { ImportDataCommandHandlerProvider } from '@/Infrastructure/Configuration/Provider/ImportDataCommandHandlerProvider'

describe('Testing ExportDataQueryHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new ImportDataCommandHandlerProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(ImportDataCommandHandler)
  })
})
