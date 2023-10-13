import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'
import { EncounterMonsterOM } from '@tests/Domain/Encounter/EncounterMonsterOM'
import { beforeEach, describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterRepository', () => {
  let sut : LocalStorageEncounterRepository

  beforeEach(() => {
    localStorage.removeItem('encounters')
    const visitor = new LocalStorageEncounterSerializerVisitor()
    const factory = new LocalStorageEncounterFactory()
    sut = new LocalStorageEncounterRepository(visitor, factory)
  })
  test('It should be of proper class', () => {
    expect(sut).toBeInstanceOf(LocalStorageEncounterRepository)
  })
  test('It should throw error when id repeated', async () => {
    const encounter = DomainEncounterOM.withName('asd')
    await sut.write(encounter)
    expect(sut.write(encounter)).rejects.toThrow(AddEncounterWritelModelError)
  })
  test('It should throw error when not found by id', () => {
    const ulid = Ulid.fromEmpty()
    expect(sut.byId(ulid)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should find existing item', async () => {
    const encounter = DomainEncounterOM.withName('asd')
    await sut.write(encounter)
    const result = await sut.byId(encounter.id())
    expect(result.ulid).toBe(encounter.id().value())
  })
  test('It should throw error when encounter not found by ulid', () => {
    const ulid = Ulid.fromEmpty()
    expect(sut.byUlid(ulid)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should find encounter by Ulid', async () => {
    const encounter = DomainEncounterOM.withName('asd')
    await sut.write(encounter)
    const result = await sut.byUlid(encounter.id())
    expect(result).toBeInstanceOf(DomainEncounter)
    expect(result.id().value()).toBe(encounter.id().value())
  })
  test('It should throw error when updating non existing encounter', () => {
    const encounter = DomainEncounterOM.withName('asd')
    expect(sut.update(encounter)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should update encounter properly', async () => {
    const encounter = DomainEncounterOM.withName('asd')
    await sut.write(encounter)
    const monster = EncounterMonsterOM.random()
    encounter.addMonster(monster)
    await sut.update(encounter)
    const updatedEncounter = await sut.byUlid(encounter.id())
    // @ts-ignore
    expect(updatedEncounter.monsters()).toHaveLength(1)
  })
  test('It should return empty array when no encounters found', async () => {
    const result = await sut.all()
    expect(result).toHaveLength(0)
  })
  test('It should return all encounters found', async () => {
    const encounter = DomainEncounterOM.withName('asd')
    await sut.write(encounter)
    const anotherEncounter = DomainEncounterOM.withName('qwe')
    await sut.write(anotherEncounter)
    const result = await sut.all()
    expect(result).toHaveLength(2)
    const first = result[0]
    expect(first.ulid).toBe(encounter.id().value())
    const second = result[1]
    expect(second.ulid).toBe(anotherEncounter.id().value())
  })
})
