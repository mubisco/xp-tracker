import { ExportFileName } from '@/Domain/Configuration/ExportFileName'
import { describe, expect, test } from 'vitest'

describe('Testing ExportFileName', () => {
  test('It should be of proper class', () => {
    const sut = ExportFileName.fromString('valid_filename')
    expect(sut).toBeInstanceOf(ExportFileName)
  })
  test('It should return proper value', () => {
    const sut = ExportFileName.fromString('valid_filename')
    expect(sut.value()).toBe('valid_filename.json')
  })
  test('It should throw exception when filename not valid', () => {
    expect(() => { ExportFileName.fromString('Not valid file') }).toThrow(RangeError)
  })

  test('It should throw exception when filename has extension', () => {
    expect(() => { ExportFileName.fromString('not_valid_filename.json') }).toThrow(RangeError)
  })
})
