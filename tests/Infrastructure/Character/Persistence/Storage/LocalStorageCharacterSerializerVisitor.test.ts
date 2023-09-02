import { BasicCharacter } from '@/Domain/Character/BasicCharacter'
import { CharacterName } from '@/Domain/Character/CharacterName'
import { Experience } from '@/Domain/Character/Experience'
import { Health } from '@/Domain/Character/Health'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { describe, test, expect } from 'vitest'

describe('Testing BasicCharacter', () => {
  test('It should be of proper class', () => {
    const sut = new LocalStorageCharacterSerializerVisitor()
    expect(sut).toBeInstanceOf(LocalStorageCharacterSerializerVisitor)
  })
  test('It should serialize character', () => {
    const sut = new LocalStorageCharacterSerializerVisitor()
    const character = BasicCharacter.fromValues(
      CharacterName.fromString('Darling'),
      Experience.fromXp(35456),
      Health.fromValues(25, 12)
    )
    const result = sut.visitBasicCharacter(character)
    const characterId = character.id().value()
    const expectedResult = `{"id":"${characterId}","name":"Darling","hitpoints":{"max":25,"current":12},"experiencePoints":35456}`
    expect(result).equals(expectedResult)
  })
})
