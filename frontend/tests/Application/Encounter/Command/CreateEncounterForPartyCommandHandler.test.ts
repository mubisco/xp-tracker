import { CreateEncounterForPartyCommand } from '@/Application/Encounter/Command/CreateEncounterForPartyCommand'
import { CreateEncounterForPartyCommandHandler } from '@/Application/Encounter/Command/CreateEncounterForPartyCommandHandler'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { EncounterForPartyWriteModelFailingStub } from '@tests/Domain/Encounter/EncounterForPartyWriteModelFailingStub'
import { EncounterForPartyWriteModelSpy } from '@tests/Domain/Encounter/EncounterForPartyWriteModelSpy'
import { describe, expect, test } from 'vitest'

describe('Testing CreateEncounterForPartyCommandHandler', () => {
  test('It should throw error when party ulid has wrong format', () => {
    const sut = new CreateEncounterForPartyCommandHandler(new EncounterForPartyWriteModelFailingStub())
    const command = new CreateEncounterForPartyCommand('', 'Chindas')
    expect(sut.handle(command)).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter name has wrong format', () => {
    const sut = new CreateEncounterForPartyCommandHandler(new EncounterForPartyWriteModelFailingStub())
    const command = new CreateEncounterForPartyCommand('01HWZJ2R0RF8F2HC08NKCPZ5E7', '')
    expect(sut.handle(command)).rejects.toThrow(RangeError)
  })
  test('It should throw error when encounter cannot be stored', () => {
    const sut = new CreateEncounterForPartyCommandHandler(new EncounterForPartyWriteModelFailingStub())
    const command = new CreateEncounterForPartyCommand('01HWZHBFW8EYHF0TZ9A7BT37G7', 'Chindas')
    expect(sut.handle(command)).rejects.toThrow(EncounterWriteModelError)
  })
  test('It should store data properly', async () => {
    const spy = new EncounterForPartyWriteModelSpy()
    const sut = new CreateEncounterForPartyCommandHandler(spy)
    const command = new CreateEncounterForPartyCommand('01HWZHBFW8EYHF0TZ9A7BT37G7', 'Chindas')
    await sut.handle(command)
    expect(spy.storedPartyUlid).toBe('01HWZHBFW8EYHF0TZ9A7BT37G7')
    expect(spy.storedEncounter).not.toBeNull()
    expect(spy.storedEncounter?.name()).toBe('Chindas')
  })
})
