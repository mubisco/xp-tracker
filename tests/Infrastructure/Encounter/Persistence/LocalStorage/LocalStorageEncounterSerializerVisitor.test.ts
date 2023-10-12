import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'
import { describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterSerializerVisitor', () => {
  test('It should visit encounter properly', () => {
    const sut = new LocalStorageEncounterSerializerVisitor()
    const encounter = DomainEncounterOM.withName('Pollos')
    const result = encounter.visit(sut)
    const expectedResult = `{"ulid":"${encounter.id().value()}","name":"Pollos"}`
    expect(result).toBe(expectedResult)
  })
})
