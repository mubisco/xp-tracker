import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { BasicCharacterOM } from '@tests/Domain/Character/BasicCharacterOM'
import { beforeEach, describe, test, expect } from 'vitest'

describe('Testing BasicCharacter', () => {
  let visitor: LocalStorageCharacterSerializerVisitor
  let sut: LocalStorageCharacterRepository

  beforeEach(() => {
    visitor = new LocalStorageCharacterSerializerVisitor()
    sut = new LocalStorageCharacterRepository(visitor)
  })
  test('It should store character properly', async () => {
    const character = BasicCharacterOM.random()
    await sut.invoke(character)
    const result = localStorage.getItem('characters') ?? '{}'
    const parsedResult = JSON.parse(result)
    expect(parsedResult[character.id().value()]).equals(character.visit(visitor))
  })

  test('It should throw error if character already exists', () => {
    const character = BasicCharacterOM.random()
    sut.invoke(character)
    expect(sut.invoke(character)).rejects.toThrowError(CharacterWriteModelError)
  })
})
