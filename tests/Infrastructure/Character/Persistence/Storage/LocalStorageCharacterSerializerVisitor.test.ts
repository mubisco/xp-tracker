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
    const expectedResult = `{"id":"${characterId}","name":"Darling","maxHitpoints":25,"currentHitpoints":12,"experiencePoints":345,"level":2,"nextLevel":900}`
    expect(result).equals(expectedResult)
  })
})
