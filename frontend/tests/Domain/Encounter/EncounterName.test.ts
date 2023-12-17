import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { describe, expect, test } from 'vitest'

describe('Testing EncounterName', () => {
  test('It should return proper value', () => {
    const sut = EncounterName.fromString('Gorilas en la niebla')
    expect(sut.value()).toBe('Gorilas en la niebla')
  })
  test('It should return proper value', () => {
    const sut = EncounterName.fromString('Gorilas en la jungla')
    expect(sut.value()).toBe('Gorilas en la jungla')
  })
  test('It should throw error if name empty', () => {
    expect(() => {
      EncounterName.fromString('')
    }).toThrow(RangeError)
  })
})
