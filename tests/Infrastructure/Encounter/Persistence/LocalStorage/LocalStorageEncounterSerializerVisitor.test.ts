import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'
import { describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterSerializerVisitor', () => {
  test('It should be of proper class', () => {
    const sut = new LocalStorageEncounterSerializerVisitor()
    expect(sut).toBeInstanceOf(LocalStorageEncounterSerializerVisitor)
  })
  test('It should visit character properly', () => {
    const sut = new LocalStorageEncounterSerializerVisitor()
    const encounter = DomainEncounterOM.withName('Pollos')
    const result = encounter.visit(sut)
    const expectedResult = `{"id":"${encounter.id().value()}","name":"Pollos"}`
    expect(result).toBe(expectedResult)
  })
})
