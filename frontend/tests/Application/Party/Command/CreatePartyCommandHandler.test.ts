import { CreatePartyCommand } from '@/Application/Party/Command/CreatePartyCommand'
import { CreatePartyCommandHandler } from '@/Application/Party/Command/CreatePartyCommandHandler'
import { PartyWriteModelError } from '@/Domain/Party/PartyWriteModelError'
import { PartyWriteModelFailingStub } from '@tests/Domain/Party/PartyWriteModelFailingStub'
import { PartyWriteModelSpy } from '@tests/Domain/Party/PartyWriteModelSpy'
import { describe, expect, test } from 'vitest'

describe('Testing CreatePartyCommandHandler', () => {
  test('It should be of proper class', () => {
    const sut = new CreatePartyCommandHandler(new PartyWriteModelFailingStub())
    expect(sut).toBeInstanceOf(CreatePartyCommandHandler)
  })
  test('It should throw error when wrong name', () => {
    const command = new CreatePartyCommand('')
    const sut = new CreatePartyCommandHandler(new PartyWriteModelFailingStub())
    expect(sut.handle(command)).rejects.toThrow(RangeError)
  })
  test('It should throw error when party cannot be created', () => {
    const command = new CreatePartyCommand('Cuchufletas')
    const sut = new CreatePartyCommandHandler(new PartyWriteModelFailingStub())
    expect(sut.handle(command)).rejects.toThrow(PartyWriteModelError)
  })
  test('It should store party properly', async () => {
    const command = new CreatePartyCommand('Cuchufletas')
    const spy = new PartyWriteModelSpy()
    const sut = new CreatePartyCommandHandler(spy)
    await sut.handle(command)
    expect(spy.party).not.toBeNull()
  })
})
