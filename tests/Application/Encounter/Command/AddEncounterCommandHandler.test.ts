import { AddEncounterCommand } from '@/Application/Encounter/Command/AddEncounterCommand'
import { AddEncounterCommandHandler } from '@/Application/Encounter/Command/AddEncounterCommandHandler'
import { AddEncounterWritelModelError } from '@/Domain/Encounter/AddEncounterWriteModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { AddEncounterWriteModelSpy } from './AddEncounterWriteModelSpy'
import { PartyTresholdReadModelDummy } from './PartyTresholdReadModelDummy'

describe('Testing AddEncounterCommandHandler', () => {
  let sut: AddEncounterCommandHandler
  let writeModel: AddEncounterWriteModelSpy
  let partyTresholdReadModelDummy: PartyTresholdReadModelDummy
  beforeEach(() => {
    writeModel = new AddEncounterWriteModelSpy()
    partyTresholdReadModelDummy = new PartyTresholdReadModelDummy()
    sut = new AddEncounterCommandHandler(writeModel, partyTresholdReadModelDummy)
  })
  test('It should be of proper class', () => {
    expect(sut).toBeInstanceOf(AddEncounterCommandHandler)
  })
  test('It should throw error when encounter name not valid', () => {
    expect(sut.handle(new AddEncounterCommand(''))).rejects.toThrow(RangeError)
  })
  test('It should throw error when write not possible', () => {
    writeModel.shouldFail = true
    expect(sut.handle(new AddEncounterCommand('Bichoños'))).rejects.toThrow(AddEncounterWritelModelError)
  })
  test('It should add encounter successfully', async () => {
    await sut.handle(new AddEncounterCommand('Bichoños'))
    expect(writeModel.writeSuccessful).toBe(true)
    const encounter = writeModel.encounter
    expect(encounter).not.toBeNull()
    // @ts-ignore
    expect(encounter.level()).toBe('EASY')
  })
})
