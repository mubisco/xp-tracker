import { describe, test, expect } from 'vitest'
import { isValid } from 'ulidx'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

describe('Testing CharacterId', () => {
  test('It should be of proper class', () => {
    const sut = Ulid.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    expect(sut).toBeInstanceOf(Ulid)
  })
  test('It should throw error when wrong value', () => {
    expect(() => {
      Ulid.fromString('01F7DKCVCVDZN1Z5Q4FWANHHC')
    }).toThrow(RangeError)
  })
  test('It should return proper value', () => {
    const sut = Ulid.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    expect(sut.value()).toBe('01F7DKCVCVDZN1Z5Q4FWANHHCC')
  })
  test('It should return proper value from empty constructor', () => {
    const sut = Ulid.fromEmpty()
    expect(isValid(sut.value())).toBe(true)
  })
  test('It should return different ulids from different instances', () => {
    const sut = Ulid.fromEmpty()
    const anotherSut = Ulid.fromEmpty()
    expect(sut.equals(anotherSut)).toBe(false)
  })
  test('It should return equality when same Id', () => {
    const sut = Ulid.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    const anotherSut = Ulid.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    expect(sut.equals(anotherSut)).toBe(true)
  })
})
