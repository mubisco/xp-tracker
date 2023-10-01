import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'
import { beforeEach, describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterRepository', () => {
  let sut : LocalStorageEncounterRepository

  beforeEach(() => {
    localStorage.removeItem('encounters')
    const visitor = new LocalStorageEncounterSerializerVisitor()
    sut = new LocalStorageEncounterRepository(visitor)
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
})
