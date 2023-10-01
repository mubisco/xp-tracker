import { FindEncounterByIdQuery } from '@/Application/Encounter/Query/FindEncounterByIdQuery'
import { FindEncounterByIdQueryHandler } from '@/Application/Encounter/Query/FindEncounterByIdQueryHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { EncounterReadModelError } from '@/Domain/Encounter/EncounterReadModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { FindEncounterReadModelDummy } from './FindEncounterReadModelDummy'

describe('Testing FindEncounterByIdQueryHandler', () => {
  let sut: FindEncounterByIdQueryHandler
  let writeModel: FindEncounterReadModelDummy
  const query = new FindEncounterByIdQuery('01HB4CRC4FN1V6TVW2DYHMA68F')
  beforeEach(() => {
    writeModel = new FindEncounterReadModelDummy()
    sut = new FindEncounterByIdQueryHandler(writeModel)
  })
  test('It should be of proper class', () => {
    expect(sut).toBeInstanceOf(FindEncounterByIdQueryHandler)
  })
  test('It should throw error when EncounterId wrong', () => {
    expect(
      sut.handle(new FindEncounterByIdQuery('01HB4CRC4FN1V6TVW2DYHMA68'))
    ).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter not found', () => {
    writeModel.shouldNotFind = true
    expect(sut.handle(query)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should throw error when encounter cannot be retrieved', () => {
    writeModel.shouldFail = true
    expect(sut.handle(query)).rejects.toThrow(EncounterReadModelError)
  })
  test('It should return proper Dto', async () => {
    const result = await sut.handle(query)
    expect(result.ulid).toBe(query.encounterId)
  })
})
