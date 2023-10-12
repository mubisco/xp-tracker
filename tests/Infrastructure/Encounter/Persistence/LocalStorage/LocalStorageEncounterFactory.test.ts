import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { LocalStorageEncounterFactory } from '@/Infrastructure/Encounter/Persistence/Storage/LocalStorageEncounterFactory'
import { describe, expect, test } from 'vitest'

describe('Testing LocalStorageEncounterFactory', () => {
  test('It should return proper DomainEncounter', () => {
    const sut = new LocalStorageEncounterFactory()
    const dto = { ulid: '01HCJBW16FTABFGZRC5V3835AP', name: 'Encounter name', monsters: [] }
    const result = sut.make(dto)
    expect(result).toBeInstanceOf(DomainEncounter)
    expect(result.id().value()).toBe(dto.ulid)
    expect(result.name().value()).toBe(dto.name)
    expect(result.monsters()).toHaveLength(0)
  })

  test('It should return proper DomainEncounter when monsters added', () => {
    const sut = new LocalStorageEncounterFactory()
    const dto = {
      ulid: '01HCJBW16FTABFGZRC5V3835AP',
      name: 'Encounter name',
      monsters: [
        { name: 'Orco feo', xp: 500, cr: '1/2' },
        { name: 'Kobold chungo', xp: 400, cr: '1/4' }
      ]
    }
    const result = sut.make(dto)
    expect(result.monsters()).toHaveLength(2)
  })
})
