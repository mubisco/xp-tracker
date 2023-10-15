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

describe('Testing UpdatePartyExperienceWhenEncounterWasFinishedEventHandler', () => {
  let failingPartyRepository: FailingPartyRepositoryDummy
  let notUpdatablePartyRepository: NotUpdatablePartyRepositoryDummy
  let partyRepositoryDummy: PartyRepositoryDummy
  let failingWriteModel: FailingUpdateExperiencePartyWriteModel
  let writeModelSpy: UpdateExperiencePartyWriteModelSpy

  beforeEach(() => {
    failingPartyRepository = new FailingPartyRepositoryDummy()
    notUpdatablePartyRepository = new NotUpdatablePartyRepositoryDummy()
    partyRepositoryDummy = new PartyRepositoryDummy()
    failingWriteModel = new FailingUpdateExperiencePartyWriteModel()
    writeModelSpy = new UpdateExperiencePartyWriteModelSpy()
  })
  test('It should be of proper class', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(failingPartyRepository, failingWriteModel)
    expect(sut).toBeInstanceOf(UpdatePartyExperienceWhenEncounterWasFinishedEventHandler)
  })
  test('It should throw error when Experience points not valid', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(failingPartyRepository, failingWriteModel)
    const badEvent = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', -1)
    expect(sut.handle(badEvent)).rejects.toThrow(RangeError)
  })
  test('It should throw error when party cannot be loaded', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(failingPartyRepository, failingWriteModel)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    expect(sut.handle(event)).rejects.toThrow(PartyRepositoryError)
  })
  test('It should throw error when party experience cannot be updated', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(notUpdatablePartyRepository, failingWriteModel)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    expect(sut.handle(event)).rejects.toThrow(RangeError)
  })
  test('It should throw error when party cannot be updated', () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(partyRepositoryDummy, failingWriteModel)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    expect(sut.handle(event)).rejects.toThrow(PartyWriteModelError)
  })
  test('It should update experience points properly', async () => {
    const sut = new UpdatePartyExperienceWhenEncounterWasFinishedEventHandler(partyRepositoryDummy, writeModelSpy)
    const event = new EncounterWasFinished('01HCSAPRM9H79111WHMYRGYSB3', 250)
    await sut.handle(event)
    expect(writeModelSpy.updatedPoints).toBe(250)
  })
})
