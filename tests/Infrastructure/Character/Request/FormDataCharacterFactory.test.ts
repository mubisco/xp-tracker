import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterFactoryError } from '@/Domain/Character/CharacterFactoryError'
import { FormDataCharacterFactory } from '@/Infrastructure/Character/Request/FormDataCharacterFactory'
import { describe, test, expect } from 'vitest'

describe('Testing BasicCharacter', () => {
  test('It should be of proper class', () => {
    const sut = new FormDataCharacterFactory()
    expect(sut).toBeInstanceOf(FormDataCharacterFactory)
  })
  test('It should throw error when data empty', () => {
    const sut = new FormDataCharacterFactory()
    expect(() => {
      sut.make({})
    }).toThrow(CharacterFactoryError)
  })
  test('It should throw error when name data does not exists', () => {
    const sut = new FormDataCharacterFactory()
    const data = { xp: 35000, hp: 54 }
    expect(() => {
      sut.make(data)
    }).toThrow(CharacterFactoryError)
  })
  test('It should throw error when xp data does not exists', () => {
    const sut = new FormDataCharacterFactory()
    const data = { name: 'Darling', hp: 54 }
    expect(() => {
      sut.make(data)
    }).toThrow(CharacterFactoryError)
  })
  test('It should throw error when hp data does not exists', () => {
    const sut = new FormDataCharacterFactory()
    const data = { name: 'Darling', xp: 5400 }
    expect(() => {
      sut.make(data)
    }).toThrow(CharacterFactoryError)
  })
  test('It should throw error when xp wrong value', () => {
    const sut = new FormDataCharacterFactory()
    const data = { name: 'Darling', hp: 54, xp: -1 }
    expect(() => {
      sut.make(data)
    }).toThrow(CharacterFactoryError)
  })
  test('It should return proper Character instance', () => {
    const sut = new FormDataCharacterFactory()
    const character = sut.make({ name: 'Darling', xp: 35000, hp: 54 })
    expect(character).toBeInstanceOf(BasicCharacter)
    const basicCharacter = character as BasicCharacter
    expect(basicCharacter.name().value()).equals('Darling')
    expect(basicCharacter.hitPoints().current).equals(54)
    expect(basicCharacter.hitPoints().max).equals(54)
    expect(basicCharacter.experience().actual).equals(35000)
  })
})
