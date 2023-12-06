import { DeleteCharacterCommand } from '@/Application/Character/Command/DeleteCharacterCommand'
import { DeleteCharacterCommandHandler } from '@/Application/Character/Command/DeleteCharacterCommandHandler'
import { CharacterNotFoundError } from '@/Domain/Character/CharacterNotFoundError'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { beforeEach, describe, expect, test } from 'vitest'
import { DeleteCharacterWriteModelStub } from './DeleteCharacterWriteModelStub'
import { InMemoryEventBusSpy } from '@tests/Application/Encounter/Command/InMemoryEventBusSpy'
import { PartyWasUpdated } from '@/Domain/Character/Party/PartyWasUpdated'

describe('Testing DeleteCharacterCommandHandler', () => {
  let sut: DeleteCharacterCommandHandler
  let writeModel: DeleteCharacterWriteModelStub
  let inMemoryEventBus: InMemoryEventBusSpy
  const command = new DeleteCharacterCommand('01HB0V6TZFYR4XEG3FJAS177J4')

  beforeEach(() => {
    inMemoryEventBus = new InMemoryEventBusSpy()
    writeModel = new DeleteCharacterWriteModelStub()
    sut = new DeleteCharacterCommandHandler(writeModel, inMemoryEventBus)
  })
  test('It should be of proper class', () => {
    expect(sut).toBeInstanceOf(DeleteCharacterCommandHandler)
  })
  test('It should throw error when characterId not well formed', () => {
    expect(sut.handle(new DeleteCharacterCommand('e5bd9854-0b77-44a2-9ebd-25a0b5f15196'))).rejects.toThrow(RangeError)
  })
  test('It should throw error when character not found', () => {
    writeModel.shouldNotFind = true
    expect(sut.handle(command)).rejects.toThrow(CharacterNotFoundError)
  })
  test('It should throw error when character cannot be deleted', () => {
    writeModel.shouldFail = true
    expect(sut.handle(command)).rejects.toThrow(CharacterWriteModelError)
  })
  test('It should delete character properly', async () => {
    await sut.handle(command)
    expect(writeModel.deleted).toBe(true)
  })
  test('It should send event', async () => {
    await sut.handle(command)
    const events = inMemoryEventBus.events
    expect(events).toHaveLength(1)
    expect(events[0]).toBeInstanceOf(PartyWasUpdated)
  })
})
