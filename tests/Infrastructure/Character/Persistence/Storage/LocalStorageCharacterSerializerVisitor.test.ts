import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { BasicCharacterOM } from '@tests/Domain/Character/BasicCharacterOM'
import { describe, test, expect } from 'vitest'

describe('Testing BasicCharacter', () => {
  test('It should be of proper class', () => {
    const sut = new LocalStorageCharacterSerializerVisitor()
    expect(sut).toBeInstanceOf(LocalStorageCharacterSerializerVisitor)
  })
  test('It should serialize character', () => {
    const sut = new LocalStorageCharacterSerializerVisitor()
    const character = BasicCharacterOM.withActualHp(12)
    const result = sut.visitBasicCharacter(character)
    const characterId = character.id().value()
    const expectedResult = `{"id":"${characterId}","name":"Darling","hitpoints":{"max":25,"current":12},"experiencePoints":345}`
    expect(result).equals(expectedResult)
  })
})
