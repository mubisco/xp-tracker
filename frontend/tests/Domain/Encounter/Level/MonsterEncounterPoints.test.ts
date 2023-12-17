import { MonsterEncounterPoints } from '@/Domain/Encounter/Level/MonsterEncounterPoints'
import { describe, expect, test } from 'vitest'

describe('Testing MonsterEncounterPoints', () => {
  test('It should return proper values for a single monster', () => {
    const sut = MonsterEncounterPoints.fromXpValues([25])
    expect(sut.value()).toBe(25)
  })
  test('It should return proper values for a couple of monster', () => {
    const sut = MonsterEncounterPoints.fromXpValues([25, 25])
    expect(sut.value()).toBe(75)
  })
  test('It should return proper values for three monsters', () => {
    const sut = MonsterEncounterPoints.fromXpValues([25, 50, 25])
    expect(sut.value()).toBe(200)
  })
  test('It should return proper values for seven monsters', () => {
    const sut = MonsterEncounterPoints.fromXpValues([25, 25, 25, 25, 25, 25, 25])
    expect(sut.value()).toBe(437)
  })
  test('It should return proper values for eleven monsters', () => {
    const sut = MonsterEncounterPoints.fromXpValues([25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25])
    expect(sut.value()).toBe(825)
  })
  test('It should return proper values for fifteen monsters', () => {
    const sut = MonsterEncounterPoints.fromXpValues([25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25])
    expect(sut.value()).toBe(1500)
  })
})
