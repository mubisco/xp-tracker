import { AllEncountersQueryHandler } from '@/Application/Encounter/Query/AllEncountersQueryHandler'
import { EncounterReadModelError } from '@/Domain/Encounter/EncounterReadModelError'
import { describe, expect, test } from 'vitest'
import { FailingAllEncountersReadModelDummy } from './FailingAllEncountersReadModelDummy'
import { EmptyAllEncountersReadModelDummy } from './EmptyAllEncountersReadModelDummy'
import { AllEncountersReadModelDummy } from './AllEncountersReadModelDummy'

describe('Testing AllEncountersQueryHandler', () => {
  test('It should be of proper class', () => {
    const sut = new AllEncountersQueryHandler(new FailingAllEncountersReadModelDummy())
    expect(sut).toBeInstanceOf(AllEncountersQueryHandler)
  })
  test('It should throw error when encounters cannot be loaded', () => {
    const sut = new AllEncountersQueryHandler(new FailingAllEncountersReadModelDummy())
    expect(sut.handle()).rejects.toThrow(EncounterReadModelError)
  })
  test('It should return empty array when no encounters found', async () => {
    const sut = new AllEncountersQueryHandler(new EmptyAllEncountersReadModelDummy())
    const result = await sut.handle()
    expect(result).toHaveLength(0)
  })
  test('It should return encounters', async () => {
    const sut = new AllEncountersQueryHandler(new AllEncountersReadModelDummy())
    const result = await sut.handle()
    expect(result).toHaveLength(1)
  })
})
