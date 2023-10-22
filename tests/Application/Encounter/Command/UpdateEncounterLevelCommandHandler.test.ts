import { UpdateEncounterLevelCommand } from '@/Application/Encounter/Command/UpdateEncounterLevelCommand'
import { UpdateEncounterLevelCommandHandler } from '@/Application/Encounter/Command/UpdateEncounterLevelCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { PartyTresholdReadModelError } from '@/Domain/Encounter/Party/PartyTresholdsReadModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { FailingPartyTresholdReadModelDummy } from './FailingPartyTresholdReadModelDummy'
import { PartyTresholdReadModelDummy } from './PartyTresholdReadModelDummy'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { NotFoundEncounterRepositoryDummy } from './NotFoundEncounterRepositoryDummy'
import { EncounterRepositoryDummy } from './EncounterRepositoryDummy'
import { FailingUpdateEncounterWriteModel } from './FailingUpdateEncounterWriteModel'
import { UpdateEncounterWriteModelSpy } from './UpdateEncounterWriteModelSpy'
import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'

describe('Testing UpdateEncounterLevelCommandHandler', () => {
  const command = new UpdateEncounterLevelCommand('01HDBM905GBZCSSBW5HDK8VTAJ')

  let failingPartyTresholdReadModel: FailingPartyTresholdReadModelDummy
  let partyTresholdReadModelDummy: PartyTresholdReadModelDummy
  let notFoundEncounterRepository: NotFoundEncounterRepositoryDummy
  let encounterRepositoryDummy: EncounterRepositoryDummy
  let failingUpdateEncounterWriteModel: FailingUpdateEncounterWriteModel
  let updateEncounterWriteModelSpy: UpdateEncounterWriteModelSpy

  beforeEach(() => {
    failingPartyTresholdReadModel = new FailingPartyTresholdReadModelDummy()
    partyTresholdReadModelDummy = new PartyTresholdReadModelDummy()
    notFoundEncounterRepository = new NotFoundEncounterRepositoryDummy()
    encounterRepositoryDummy = new EncounterRepositoryDummy()
    failingUpdateEncounterWriteModel = new FailingUpdateEncounterWriteModel()
    updateEncounterWriteModelSpy = new UpdateEncounterWriteModelSpy()
  })

  test('It should be of proper class', () => {
    const sut = new UpdateEncounterLevelCommandHandler(
      failingPartyTresholdReadModel,
      notFoundEncounterRepository,
      failingUpdateEncounterWriteModel
    )
    expect(sut).toBeInstanceOf(UpdateEncounterLevelCommandHandler)
  })
  test('It should throw error when wrong encounter id', () => {
    const sut = new UpdateEncounterLevelCommandHandler(
      failingPartyTresholdReadModel,
      notFoundEncounterRepository,
      failingUpdateEncounterWriteModel
    )
    const wrongUlidCommand = new UpdateEncounterLevelCommand('01HDBM905GBZCSSBW5HDK8VTA')
    expect(sut.handle(wrongUlidCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when no treshold found', () => {
    const sut = new UpdateEncounterLevelCommandHandler(
      failingPartyTresholdReadModel,
      notFoundEncounterRepository,
      failingUpdateEncounterWriteModel
    )
    expect(sut.handle(command)).rejects.toThrow(PartyTresholdReadModelError)
  })
  test('It should throw error when no encounter found', () => {
    const sut = new UpdateEncounterLevelCommandHandler(
      partyTresholdReadModelDummy,
      notFoundEncounterRepository,
      failingUpdateEncounterWriteModel
    )
    expect(sut.handle(command)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should throw error when encounter cannot be updated', () => {
    const sut = new UpdateEncounterLevelCommandHandler(
      partyTresholdReadModelDummy,
      encounterRepositoryDummy,
      failingUpdateEncounterWriteModel
    )
    expect(sut.handle(command)).rejects.toThrow(EncounterWriteModelError)
  })
  test('It should finish', async () => {
    const sut = new UpdateEncounterLevelCommandHandler(
      partyTresholdReadModelDummy,
      encounterRepositoryDummy,
      updateEncounterWriteModelSpy
    )
    await sut.handle(command)
    const updatedEncounter = updateEncounterWriteModelSpy.getUpdatedEncounter()
    expect(updatedEncounter).toBeInstanceOf(DomainEncounter)
    // expect(updatedEncounter.level()).toBe('MEDIUM')
  })
})
