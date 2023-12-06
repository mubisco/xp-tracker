import { UpdatePartyExperienceWhenEncounterWasFinishedEventHandler } from '@/Application/Character/Event/UpdatePartyExperienceWhenEncounterWasFinishedEventHandler'
import { PartyRepositoryError } from '@/Domain/Character/Party/PartyRepositoryError'
import { EncounterWasFinished } from '@/Domain/Encounter/EncounterWasFinished'
import { beforeEach, describe, expect, test } from 'vitest'
import { PartyRepositoryDummy } from './PartyRepositoryDummy'
import { NotUpdatablePartyRepositoryDummy } from './NotUpdateblePartyRepositoryDummy'
import { FailingPartyRepositoryDummy } from './FailingPartyRepositoryDummy'
import { PartyWriteModelError } from '@/Domain/Character/Party/PartyWriteModelError'
import { UpdateExperiencePartyWriteModelSpy } from './UpdateExperiencePartyWriteModelSpy'
import { FailingUpdateExperiencePartyWriteModel } from './FailingUpdateExperiencePartyWriteModel'
import { InMemoryEventBusSpy } from '@tests/Application/Encounter/Command/InMemoryEventBusSpy'
import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'

describe('Testing UpdatePartyExperienceWhenEncounterWasFinishedEventHandler', () => {
  let failingPartyRepository: FailingPartyRepositoryDummy
  let notUpdatablePartyRepository: NotUpdatablePartyRepositoryDummy
  let partyRepositoryDummy: PartyRepositoryDummy
  let failingWriteModel: FailingUpdateExperiencePartyWriteModel
  let writeModelSpy: UpdateExperiencePartyWriteModelSpy
  let eventBusSpy: InMemoryEventBusSpy

  beforeEach(() => {
    failingPartyRepository = new FailingPartyRepositoryDummy()
    notUpdatablePartyRepository = new NotUpdatablePartyRepositoryDummy()
    partyRepositoryDummy = new PartyRepositoryDummy()
    failingWriteModel = new FailingUpdateExperiencePartyWriteModel()
    writeModelSpy = new UpdateExperiencePartyWriteModelSpy()
    eventBusSpy = new InMemoryEventBusSpy()
  })
  test('It should throw error when Experience points not valid', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(failingPartyRepository, failingWriteModel, eventBusSpy)
    const badEvent = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', -1)
    expect(sut.handle(badEvent)).rejects.toThrow(RangeError)
  })
  test('It should throw error when party cannot be loaded', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(failingPartyRepository, failingWriteModel, eventBusSpy)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    expect(sut.handle(event)).rejects.toThrow(PartyRepositoryError)
  })
  test('It should throw error when party experience cannot be updated', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(notUpdatablePartyRepository, failingWriteModel, eventBusSpy)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    expect(sut.handle(event)).rejects.toThrow(RangeError)
  })
  test('It should throw error when party cannot be updated', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(partyRepositoryDummy, failingWriteModel, eventBusSpy)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    expect(sut.handle(event)).rejects.toThrow(PartyWriteModelError)
  })
  test('It should update experience points properly', async () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(partyRepositoryDummy, writeModelSpy, eventBusSpy)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    await sut.handle(event)
    expect(writeModelSpy.updatedPoints).toBe(250)
  })
  test('It should push events when experience updated', async () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(partyRepositoryDummy, writeModelSpy, eventBusSpy)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    await sut.handle(event)
    expect(eventBusSpy.events).toHaveLength(1)
    expect(eventBusSpy.events[0]).toBeInstanceOf(PartyWasUpdated)
  })
  test('It should return true to appropiate event', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(partyRepositoryDummy, writeModelSpy, eventBusSpy)
    expect(sut.listensTo('EncounterWasFinished')).toBe(true)
  })
})
