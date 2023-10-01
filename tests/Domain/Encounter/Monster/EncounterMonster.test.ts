import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { describe, expect, test } from 'vitest'

describe('Testing EncounterMonster', () => {
  test('It should throw error when name empty', () => {
    expect(() => {
      new EncounterMonster('', 2500, '1/2')
    }).toThrow(RangeError)
  })
  test('It should throw error when XP below 1', () => {
    expect(() => {
      new EncounterMonster('Shadow Lord', 0, '1/2')
    }).toThrow(RangeError)
  })
  // test('It should throw error when challenge rating wrong', () => {
  //   expect(() => {
  //     new EncounterMonster('Shadow Lord', 2500, '0')
  //   }).toThrow(RangeError)
  // })
  test('It should return proper values', () => {
    const sut = new EncounterMonster('Shadow Lord', 2500, '1/2')
    expect(sut.name()).toBe('Shadow Lord')
    expect(sut.xp()).toBe(2500)
    expect(sut.challengeRating()).toBe('1/2')
  })
  test('It should return proper values', () => {
    const sut = new EncounterMonster('Orco', 1000, '1')
    expect(sut.name()).toBe('Orco')
    expect(sut.xp()).toBe(1000)
    expect(sut.challengeRating()).toBe('1')
  })
})
