import { Party } from '@/Domain/Party/Party'
import { isValid } from 'ulidx'
import { describe, expect, test } from 'vitest'

describe('Testing CreatePartyCommandHandler', () => {
  test('It should throw error when wrong ulid', () => {
    expect(() => {
      Party.fromValues('asd', 'Cuchufletas')
    }).toThrow(RangeError)
  })

  test('It should throw error when wrong name', () => {
    expect(() => {
      Party.fromValues('01HWWNJQA8YR0AJX6EHCHK8FWA', '')
    }).toThrow(RangeError)
  })

  test('It should return proper values', () => {
    const sut = Party.fromValues('01HWWNJQA8YR0AJX6EHCHK8FWA', 'Cuchufletas')
    expect(sut.id()).toBe('01HWWNJQA8YR0AJX6EHCHK8FWA')
    expect(sut.name()).toBe('Cuchufletas')
  })

  test('It should return proper values when only name provided', () => {
    const sut = Party.fromName('Cuchufletas')
    const id = sut.id()
    expect(isValid(id)).toBe(true)
    expect(sut.name()).toBe('Cuchufletas')
  })
})
