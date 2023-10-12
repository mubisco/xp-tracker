import { LocalStorageEncounterSerializerVisitor } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterSerializerVisitor'
import { DomainEncounterOM } from '@tests/Domain/Encounter/DomainEncounterOM'
import { EncounterMonsterOM } from '@tests/Domain/Encounter/EncounterMonsterOM'
import { describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterSerializerVisitor', () => {
  test('It should visit encounter properly', () => {
    const sut = new LocalStorageEncounterSerializerVisitor()
    const encounter = DomainEncounterOM.withName('Pollos')
    const result = encounter.visit(sut)
    const expectedResult = `{"ulid":"${encounter.id().value()}","name":"Pollos","monsters":[]}`
    expect(result).toBe(expectedResult)
  })
  test('It should visit encounter with monsters properly', () => {
    const sut = new LocalStorageEncounterSerializerVisitor()
    const encounter = DomainEncounterOM.withName('Pollos')
    const monster = EncounterMonsterOM.random()
    encounter.addMonster(monster)
    const result = encounter.visit(sut)
    const expectedResult = {
      ulid: encounter.id().value(),
      name: 'Pollos',
      monsters: [
        {
          name: 'Some name',
          xp: 2500,
          cr: '1/2'
        }
      ]
    }
    expect(result).toBe(JSON.stringify(expectedResult))
  })
})
