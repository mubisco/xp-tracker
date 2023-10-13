import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { describe, expect, test } from 'vitest'

describe('Testing EncounterMonster', () => {
  test('It should throw error when name empty', () => {
    expect(() => {
      EncounterMonster.fromValues('', 2500, '1/2')
    }).toThrow(RangeError)
  })
  test('It should throw error when XP below 1', () => {
    expect(() => {
      EncounterMonster.fromValues('Shadow Lord', 0, '1/2')
    }).toThrow(RangeError)
  })
  test('It should throw error when challenge rating wrong', () => {
    expect(() => {
      EncounterMonster.fromValues('Shadow Lord', 2500, '1/3')
    }).toThrow(RangeError)
  })
  test('It should return proper values', () => {
    const sut = EncounterMonster.fromValues('Shadow Lord', 2500, '1/2')
    expect(sut.name()).toBe('Shadow Lord')
    expect(sut.xp()).toBe(2500)
    expect(sut.challengeRating()).toBe('1/2')
  })
  test('It should return false when Monster not equals to another', () => {
    const sut = EncounterMonster.fromValues('Shadow Lord', 2500, '1/2')
    const another = EncounterMonster.fromValues('Shadow Lord 2', 2500, '1/2')
    const xpDifferent = EncounterMonster.fromValues('Shadow Lord', 2000, '1/2')
    const crDifferent = EncounterMonster.fromValues('Shadow Lord', 2500, '1/8')
    expect(sut.equalsTo(another)).toBe(false)
    expect(sut.equalsTo(xpDifferent)).toBe(false)
    expect(sut.equalsTo(crDifferent)).toBe(false)
  })
  test('It should return true when Monster equals to another', () => {
    const sut = EncounterMonster.fromValues('Shadow Lord', 2500, '1/2')
    const another = EncounterMonster.fromValues('Shadow Lord', 2500, '1/2')
    expect(sut.equalsTo(another)).toBe(true)
  })
})
