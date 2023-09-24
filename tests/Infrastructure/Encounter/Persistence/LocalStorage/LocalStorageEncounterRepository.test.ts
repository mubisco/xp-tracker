import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { LocalStorageEncounterRepository } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterRepository'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'
import { beforeEach, describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterRepository', () => {
  let sut : LocalStorageEncounterRepository

  beforeEach(() => {
    localStorage.removeItem('encounters')
    sut = new LocalStorageEncounterRepository()
  })
  test('It should be of proper class', () => {
    expect(sut).toBeInstanceOf(LocalStorageEncounterRepository)
  })
  test('It should throw error when id repeated', async () => {
    const encounter = DomainEncounterOM.withName('asd')
    await sut.write(encounter)
    expect(sut.write(encounter)).rejects.toThrow(AddEncounterWritelModelError)
  })
  test('It should write item properly', async () => {
    const encounter = DomainEncounterOM.withName('asd')
    await sut.write(encounter)
    const encounters = localStorage.getItem('encounters') ?? '{}'
    const parsedEncounters = JSON.parse(encounters)
    expect(parsedEncounters[encounter.id().value()]).not.toBeUndefined()
    // expect(parsedEncounters[encounter.id().value()]).toBe('stringifiedResult')
  })
})
