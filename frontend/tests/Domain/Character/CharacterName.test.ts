import { CharacterName } from '@/Domain/Character/CharacterName'
import { describe, test, expect } from 'vitest'

describe('Testing CharacterName', () => {
  test('It should throw exception when name empty', () => {
    expect(() => {
      CharacterName.fromString('')
    }).toThrow(RangeError)
  })
  test('It should return proper value', () => {
    const sut = CharacterName.fromString('Darling')
    expect(sut.value()).equals('Darling')
  })
})
