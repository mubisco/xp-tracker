import { ChallengeRating } from '@/Domain/Encounter/Monster/ChallengeRating'
import { describe, expect, test } from 'vitest'

const validValues = ['0', '1/8', '1/4', '1/2', '1', '10', '24', '30']
const wrongValues = ['-1', '1/3', '31']

describe('Testing ChallengeRating', () => {
  test('It should throw error with wrong value', () => {
    for (const value of wrongValues) {
      expect(() => {
        ChallengeRating.fromString(value)
      }).toThrow(RangeError)
    }
  })
  test('It should return proper values', () => {
    for (const value of validValues) {
      const sut = ChallengeRating.fromString(value)
      expect(sut.value()).toBe(value)
    }
  })
})
