import { FinishEncounterCommand } from '@/Application/Encounter/Command/FinishEncounterCommand'
import { FinishEncounterCommandHandler } from '@/Application/Encounter/Command/FinishEncounterCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { beforeEach, describe, expect, test } from 'vitest'
import { NotFoundEncounterRepositoryDummy } from './NotFoundEncounterRepositoryDummy'
import { EncounterRepositoryDummy } from './EncounterRepositoryDummy'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { InMemoryEventBusSpy } from './InMemoryEventBusSpy'
import { UpdateEncounterWriteModelSpy } from './UpdateEncounterWriteModelSpy'
import { FailingUpdateEncounterWriteModel } from './FailingUpdateEncounterWriteModel'
import { EncounterStatus } from '@/Domain/Encounter/EncounterStatus'

describe('Testing FinishEncounterCommandHandler', () => {
  let notFoundEncounterRepository: NotFoundEncounterRepositoryDummy
  let encounterRepositoryDummy: EncounterRepositoryDummy
  let failingUpdateWriteModel: FailingUpdateEncounterWriteModel
  let dummyUpdateWriteModel: UpdateEncounterWriteModelSpy
  let eventBusSpy: InMemoryEventBusSpy

  beforeEach(() => {
    notFoundEncounterRepository = new NotFoundEncounterRepositoryDummy()
    encounterRepositoryDummy = new EncounterRepositoryDummy()
    failingUpdateWriteModel = new FailingUpdateEncounterWriteModel()
    dummyUpdateWriteModel = new UpdateEncounterWriteModelSpy()
    eventBusSpy = new InMemoryEventBusSpy()
  })

  test('It should be of proper class', () => {
    const sut = new FinishEncounterCommandHandler(notFoundEncounterRepository, failingUpdateWriteModel, eventBusSpy)
    expect(sut).toBeInstanceOf(FinishEncounterCommandHandler)
  })
  test('It should throw error when encounterId wrong', () => {
    const sut = new FinishEncounterCommandHandler(notFoundEncounterRepository, failingUpdateWriteModel, eventBusSpy)
    const wrongUlidCommand = new FinishEncounterCommand('asd')
    expect(sut.handle(wrongUlidCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter not found', () => {
    const sut = new FinishEncounterCommandHandler(notFoundEncounterRepository, failingUpdateWriteModel, eventBusSpy)
    const command = new FinishEncounterCommand('01HCPRBYFC131V8RX9KMD2SK9P')
    expect(sut.handle(command)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should throw error when encounter cannot be updated', () => {
    const sut = new FinishEncounterCommandHandler(encounterRepositoryDummy, failingUpdateWriteModel, eventBusSpy)
    const command = new FinishEncounterCommand('01HCPRBYFC131V8RX9KMD2SK9P')
    expect(sut.handle(command)).rejects.toThrow(EncounterWriteModelError)
  })
  test('It should generate proper events', async () => {
    const sut = new FinishEncounterCommandHandler(encounterRepositoryDummy, dummyUpdateWriteModel, eventBusSpy)
    const command = new FinishEncounterCommand('01HCPRBYFC131V8RX9KMD2SK9P')
    await sut.handle(command)
    expect(eventBusSpy.events).toHaveLength(1)
    const encounter = dummyUpdateWriteModel.getUpdatedEncounter()
    expect(encounter.status()).toBe(EncounterStatus.DONE)
  })
})
