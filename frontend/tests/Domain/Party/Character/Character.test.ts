import { Character } from '@/Domain/Party/Character/Character'
import { isValid } from 'ulidx'
import { describe, expect, test } from 'vitest'

describe('Testing CreatePartyCommandHandler', () => {
  test('It should return proper values', () => {
    const sut = Character.fromValues('01HWXKZP2RSNS1J9PBK1V3P3X7', 'Chindasvinto', 120)
    expect(sut.ulid()).toBe('01HWXKZP2RSNS1J9PBK1V3P3X7')
    expect(sut.name()).toBe('Chindasvinto')
    expect(sut.xp()).toBe(120)
  })
  test('It should return proper values when no ulid given', () => {
    const sut = Character.withNameAndXp('Chindasvinto', 120)
    expect(isValid(sut.ulid())).toBe(true)
    expect(sut.name()).toBe('Chindasvinto')
    expect(sut.xp()).toBe(120)
  })
  test('It should throw error when name empty', () => {
    expect(() => {
      Character.fromValues('01HWXKZP2RSNS1J9PBK1V3P3X7', '', 120)
    }).toThrow(RangeError)
  })
  test('It should throw error when wrong ulid', () => {
    expect(() => {
      Character.fromValues('asd', 'Chindasvinto', 120)
    }).toThrow(RangeError)
  })
  test('It should throw error when experience points less than zero', () => {
    expect(() => {
      Character.fromValues('01HWXM7G2RXYW2SCNJJ38171P9', 'Chindasvinto', -1)
    }).toThrow(RangeError)
  })
})
