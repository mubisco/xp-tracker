import { Experience } from '@/Domain/Character/Experience'
import { ExperienceDto } from '@/Domain/Character/ExperienceDto'
import { describe, test, expect } from 'vitest'

describe('Testing Experience', () => {
  test('It should throw error when experiencie less than zero', () => {
    expect(() => {
      // eslint-disable-next-line
      Experience.fromXp(-1)
    }).toThrow(RangeError)
  })
  test('It should return proper data', () => {
    const sut = Experience.fromXp(35000)
    const values = sut.values()
    expect(values).toBeInstanceOf(ExperienceDto)
    expect(values.actual).toBe(35000)
    expect(values.level).toBe(8)
    expect(values.nextLevel).toBe(48000)
  })
  test('It should return proper level when more than level 20', () => {
    const sut = Experience.fromXp(360000)
    const values = sut.values()
    expect(values.level).toBe(20)
    expect(values.nextLevel).toBe(-1)
  })
  test('It should add two experience items', () => {
    const sut = Experience.fromXp(35000)
    const another = Experience.fromXp(7564)
    const result = sut.add(another)
    expect(result).toBeInstanceOf(Experience)
    expect(result).not.toEqual(sut)
    const values = result.values()
    expect(values.actual).toBe(42564)
  })
})
