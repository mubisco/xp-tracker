import { Health } from '@/Domain/Character/Health'
import { HitPointsDto } from '@/Domain/Character/HitPointsDto'
import { describe, test, expect } from 'vitest'

describe('Testing Health', () => {
  test('It should throw error when max below 0', () => {
    expect(() => {
      // eslint-disable-next-line
      Health.fromMaxHp(-1)
    }).toThrow(RangeError)
  })

  test('It should return proper values', () => {
    const sut = Health.fromMaxHp(12)
    const hitpoints = sut.hitpoints()
    expect(hitpoints).toBeInstanceOf(HitPointsDto)
    expect(hitpoints.max).equals(12)
    expect(hitpoints.current).equals(12)
  })
  test('It should return proper values when current less than max', () => {
    const sut = Health.fromValues(24, 12)
    const hitpoints = sut.hitpoints()
    expect(hitpoints.max).equals(24)
    expect(hitpoints.current).equals(12)
  })
})
