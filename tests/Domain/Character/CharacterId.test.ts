import { CharacterId } from '@/Domain/Character/CharacterId'
import { describe, test, expect } from 'vitest'
import { isValid } from 'ulidx'
import { AbstractUlid } from '@/Domain/Shared/AbstractUlid'

class DummyUlid extends AbstractUlid {}

describe('Testing CharacterId', () => {
  test('It should be of proper class', () => {
    const sut = CharacterId.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    expect(sut).toBeInstanceOf(CharacterId)
  })
  test('It should throw error when wrong value', () => {
    expect(() => {
      CharacterId.fromString('01F7DKCVCVDZN1Z5Q4FWANHHC')
    }).toThrow(RangeError)
  })
  test('It should return proper value', () => {
    const sut = CharacterId.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    expect(sut.value()).toBe('01F7DKCVCVDZN1Z5Q4FWANHHCC')
  })
  test('It should return proper value from empty constructor', () => {
    const sut = CharacterId.fromEmpty()
    expect(isValid(sut.value())).toBe(true)
  })
  test('It should return different ulids from different instances', () => {
    const sut = CharacterId.fromEmpty()
    const anotherSut = CharacterId.fromEmpty()
    expect(sut.equals(anotherSut)).toBe(false)
  })
  test('It should return equality when same Id', () => {
    const sut = CharacterId.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    const anotherSut = CharacterId.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    expect(sut.equals(anotherSut)).toBe(true)
  })
  test('It should return false equality when different classes', () => {
    const sut = CharacterId.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    const anotherSut = DummyUlid.fromString('01F7DKCVCVDZN1Z5Q4FWANHHCC')
    expect(sut.equals(anotherSut)).toBe(false)
  })
})
