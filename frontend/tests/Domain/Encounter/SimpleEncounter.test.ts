import { SimpleEncounter } from '@/Domain/Encounter/SimpleEncounter'
import { isValid } from 'ulidx'
import { describe, expect, test } from 'vitest'

describe('Testing SimpleEncounter', () => {
  test('It should throw exception when name empty', () => {
    expect(() => {
      SimpleEncounter.fromValues('01HWZHQDQ0V4WZGM1JRM1XHCW4', '')
    }).toThrow(RangeError)
  })
  test('It should throw error when wrong ulid', () => {
    expect(() => {
      SimpleEncounter.fromValues('1HWZHQDQ0V4WZGM1JRM1XHCW4', 'Chinads')
    }).toThrow(RangeError)
  })
  test('It should return proper values', () => {
    const sut = SimpleEncounter.fromValues('01HWZHQ8TR5YSB5C6KCBTQKJH3', 'Chindas')
    expect(sut.ulid()).toBe('01HWZHQ8TR5YSB5C6KCBTQKJH3')
    expect(sut.name()).toBe('Chindas')
  })
  test('It should construct object from only name', () => {
    const sut = SimpleEncounter.fromName('Chindas')
    expect(isValid(sut.ulid())).toBe(true)
    expect(sut.name()).toBe('Chindas')
  })
})
