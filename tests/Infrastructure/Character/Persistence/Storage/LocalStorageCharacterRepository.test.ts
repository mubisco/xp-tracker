import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { CharacterRepositoryError } from '@/Domain/Character/CharacterRepositoryError'
import { Experience } from '@/Domain/Character/Experience'
import { Health } from '@/Domain/Character/Health'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { beforeEach, describe, test, expect } from 'vitest'

describe('Testing BasicCharacter', () => {
  let visitor: LocalStorageCharacterSerializerVisitor
  let sut: LocalStorageCharacterRepository

  beforeEach(() => {
    visitor = new LocalStorageCharacterSerializerVisitor()
    sut = new LocalStorageCharacterRepository(visitor)
  })

  test('It should be of proper class', () => {
    expect(sut).toBeInstanceOf(LocalStorageCharacterRepository)
  })
  test('It should store character properly', async () => {
    const character = BasicCharacter.fromValues(CharacterName.fromString('Darling'), Experience.fromXp(345), Health.fromMaxHp(25))
    await sut.store(character)
    const result = localStorage.getItem('characters') ?? '{}'
    const parsedResult = JSON.parse(result)
    expect(parsedResult[character.id().value()]).equals(character.visit(visitor))
  })

  test('It should throw error if character already exists', () => {
    const character = BasicCharacter.fromValues(CharacterName.fromString('Darling'), Experience.fromXp(345), Health.fromMaxHp(25))
    sut.store(character)
    expect(sut.store(character)).rejects.toThrowError(CharacterRepositoryError)
  })
})
