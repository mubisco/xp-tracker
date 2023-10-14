import { AddEncounterExperienceToPartyCommand } from '@/Application/Encounter/Command/AddEncounterExperienceToPartyCommand'
import { AddEncounterExperienceToPartyCommandHandler } from '@/Application/Encounter/Command/AddEncounterExperienceToPartyCommandHandler'
import { EncounterNotFoundError } from '@/Domain/Encounter/EncounterNotFoundError'
import { beforeEach, describe, expect, test } from 'vitest'
import { NotFoundEncounterRepositoryDummy } from './NotFoundEncounterRepositoryDummy'
import { EmptyPartyError } from '@/Application/Encounter/Command/EmptyPartyError'
import { EncounterRepositoryDummy } from './EncounterRepositoryDummy'

describe('Testing AddEncounterExperienceToPartyCommandHandler', () => {
  let notFoundEncounterRepository: NotFoundEncounterRepositoryDummy
  let encounterRepositoryDummy: EncounterRepositoryDummy

  beforeEach(() => {
    notFoundEncounterRepository = new NotFoundEncounterRepositoryDummy()
    encounterRepositoryDummy = new EncounterRepositoryDummy()
  })

  test('It should be of proper class', () => {
    const sut = new AddEncounterExperienceToPartyCommandHandler(notFoundEncounterRepository)
    expect(sut).toBeInstanceOf(AddEncounterExperienceToPartyCommandHandler)
  })
  test('It should throw error when encounterId wrong', () => {
    const sut = new AddEncounterExperienceToPartyCommandHandler(notFoundEncounterRepository)
    const wrongUlidCommand = new AddEncounterExperienceToPartyCommand('asd')
    expect(sut.handle(wrongUlidCommand)).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter not found', () => {
    const sut = new AddEncounterExperienceToPartyCommandHandler(notFoundEncounterRepository)
    const command = new AddEncounterExperienceToPartyCommand('01HCPRBYFC131V8RX9KMD2SK9P')
    expect(sut.handle(command)).rejects.toThrow(EncounterNotFoundError)
  })
  test('It should throw error when empty party', () => {
    const sut = new AddEncounterExperienceToPartyCommandHandler(encounterRepositoryDummy)
    const command = new AddEncounterExperienceToPartyCommand('01HCPRBYFC131V8RX9KMD2SK9P')
    expect(sut.handle(command)).rejects.toThrow(EmptyPartyError)
  })
})
