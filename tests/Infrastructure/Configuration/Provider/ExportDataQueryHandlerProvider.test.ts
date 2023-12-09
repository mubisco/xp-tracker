import { ExportDataQueryHandler } from '@/Application/Configuration/Query/ExportDataQueryHandler'
import { ExportDataQueryHandlerProvider } from '@/Infrastructure/Configuration/Provider/ExportDataQueryHandlerProvider'
import { describe, expect, test } from 'vitest'

describe('Testing ExportDataQueryHandlerProvider', () => {
  test('It should return proper handler', () => {
    const sut = new ExportDataQueryHandlerProvider()
    const result = sut.provide()
    expect(result).toBeInstanceOf(ExportDataQueryHandler)
  })
})
