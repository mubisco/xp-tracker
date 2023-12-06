import { UpdateEncounterLevelsWhenPartyUpdated } from '@/Application/Encounter/Event/UpdateEncounterLevelsWhenPartyUpdated'
import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'
import { EncounterRepositoryError } from '@/Domain/Encounter/EncounterRepositoryError'
import { PartyTresholdReadModelError } from '@/Domain/Encounter/Party/PartyTresholdsReadModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { FailingPartyTresholdReadModelDummy } from '../Command/FailingPartyTresholdReadModelDummy'
import { PartyTresholdReadModelDummy } from '../Command/PartyTresholdReadModelDummy'
import { FailingEncounterRepositoryDummy } from '../Command/FailingEncounterRepositoryDummy'
import { EncounterRepositoryDummy } from '../Command/EncounterRepositoryDummy'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { FailingUpdateEncounterWriteModel } from '../Command/FailingUpdateEncounterWriteModel'
import { UpdateEncounterWriteModelSpy } from '../Command/UpdateEncounterWriteModelSpy'

describe('Testing UpdateEncounterLevelsWhenPartyUpdated', () => {
  const event = new PartyWasUpdated()
  let failingPartyTresholdReadModel: FailingPartyTresholdReadModelDummy
  let partyTresholdReadModelDummy: PartyTresholdReadModelDummy
  let failingEncounterRepository: FailingEncounterRepositoryDummy
  let encounterRepositoryDummy: EncounterRepositoryDummy
  let failingUpdateEncounterWritModel: FailingUpdateEncounterWriteModel
  let updateEncounterWriteModelSpy: UpdateEncounterWriteModelSpy

  beforeEach(() => {
    partyTresholdReadModelDummy = new PartyTresholdReadModelDummy()
    failingPartyTresholdReadModel = new FailingPartyTresholdReadModelDummy()
    failingEncounterRepository = new FailingEncounterRepositoryDummy()
    encounterRepositoryDummy = new EncounterRepositoryDummy()
    failingUpdateEncounterWritModel = new FailingUpdateEncounterWriteModel()
    updateEncounterWriteModelSpy = new UpdateEncounterWriteModelSpy()
  })
  test('It should be of proper class', () => {
    const sut = new UpdateEncounterLevelsWhenPartyUpdated(
      failingPartyTresholdReadModel,
      failingEncounterRepository,
      failingUpdateEncounterWritModel
    )
    expect(sut).toBeInstanceOf(UpdateEncounterLevelsWhenPartyUpdated)
  })
  test('It should throw error when party tresholds cannot be fetched', () => {
    const sut = new UpdateEncounterLevelsWhenPartyUpdated(
      failingPartyTresholdReadModel,
      failingEncounterRepository,
      failingUpdateEncounterWritModel
    )
    expect(sut.handle(event)).rejects.toThrow(PartyTresholdReadModelError)
  })
  test('It should throw error when encounters cannot be loaded', () => {
    const sut = new UpdateEncounterLevelsWhenPartyUpdated(
      partyTresholdReadModelDummy,
      failingEncounterRepository,
      failingUpdateEncounterWritModel
    )
    expect(sut.handle(event)).rejects.toThrow(EncounterRepositoryError)
  })
  test('It should throw error when encounters cannot be updated', () => {
    const sut = new UpdateEncounterLevelsWhenPartyUpdated(
      partyTresholdReadModelDummy,
      encounterRepositoryDummy,
      failingUpdateEncounterWritModel
    )
    expect(sut.handle(event)).rejects.toThrow(EncounterWriteModelError)
  })
  test('It should update all encounters', async () => {
    const sut = new UpdateEncounterLevelsWhenPartyUpdated(
      partyTresholdReadModelDummy,
      encounterRepositoryDummy,
      updateEncounterWriteModelSpy
    )
    await sut.handle(event)
    const updatedEncounter = updateEncounterWriteModelSpy.getUpdatedEncounter()
    expect(updatedEncounter.level()).not.toBe('UNASSIGNED')
  })
})
