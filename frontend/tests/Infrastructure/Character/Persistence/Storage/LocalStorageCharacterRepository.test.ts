import { CharacterNotFoundError } from '@/Domain/Character/CharacterNotFoundError'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { Experience } from '@/Domain/Character/Experience'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { LocalStorageCharacterRepository } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterRepository'
import { LocalStorageCharacterSerializerVisitor } from '@/Infrastructure/Character/Persistence/Storage/LocalStorageCharacterSerializerVisitor'
import { BasicCharacterOM } from '@tests/Domain/Character/BasicCharacterOM'
import { beforeEach, describe, test, expect } from 'vitest'

describe('Testing BasicCharacter', () => {
  let visitor: LocalStorageCharacterSerializerVisitor
  let sut: LocalStorageCharacterRepository

  beforeEach(() => {
    localStorage.removeItem('characters')
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

  test('It should return all characters stored', async () => {
    const character = BasicCharacterOM.random()
    const anotherCharacter = BasicCharacterOM.random()
    sut.invoke(character)
    sut.invoke(anotherCharacter)
    const characterList = await sut.read()
    expect(characterList).toHaveLength(2)
    expect(characterList[0].ulid).toBe(character.id().value())
    expect(characterList[1].ulid).toBe(anotherCharacter.id().value())
  })
  test('It should throw error when deleting character that does not exists', () => {
    expect(sut.remove(Ulid.fromEmpty())).rejects.toThrowError(CharacterNotFoundError)
  })
  test('It should delete existing character', async () => {
    const character = BasicCharacterOM.random()
    sut.invoke(character)
    await sut.remove(character.id())
    const characters = await sut.read()
    expect(characters.length).toBe(0)
  })
  test('It should return proper Party', async () => {
    const character = BasicCharacterOM.random()
    const anotherCharacter = BasicCharacterOM.random()
    sut.invoke(character)
    sut.invoke(anotherCharacter)
    const party = await sut.find()
    // @ts-ignore
    expect(party.count()).toBe(2)
  })
  test('It should update party', async () => {
    const character = BasicCharacterOM.random()
    const anotherCharacter = BasicCharacterOM.random()
    sut.invoke(character)
    sut.invoke(anotherCharacter)
    const party = await sut.find()
    party.updateExperience(Experience.fromXp(300))
    sut.updateParty(party)
    const updatedCharacters = await sut.read()
    expect(updatedCharacters[0].xp).toBe(495)
    expect(updatedCharacters[1].xp).toBe(495)
  })
})
