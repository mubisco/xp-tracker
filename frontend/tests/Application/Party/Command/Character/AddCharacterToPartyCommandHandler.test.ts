import { AddCharacterToPartyCommand } from '@/Application/Party/Command/Character/AddCharacterToPartyCommand'
import { AddCharacterToPartyCommandHandler } from '@/Application/Party/Command/Character/AddCharacterToPartyCommandHandler'
import { CharacterWriteModelError } from '@/Domain/Party/Character/CharacterWriteModelError'
import { PartyWriteModelError } from '@/Domain/Party/PartyWriteModelError'
import { CharacterWriteModelFailingStub } from '@tests/Domain/Character/Party/CharacterWriteModelFailingStub'
import { CharacterWriteModelSpy } from '@tests/Domain/Character/Party/CharacterWriteModelSpy'
import { PartyWriteModelFailingStub } from '@tests/Domain/Party/PartyWriteModelFailingStub'
import { PartyWriteModelSpy } from '@tests/Domain/Party/PartyWriteModelSpy'
import { describe, expect, test } from 'vitest'

describe('Testing CreatePartyCommandHandler', () => {
  test('It should throw error when wrong party ulid', () => {
    const command = new AddCharacterToPartyCommand('', 'Chindasvinto', 200)
    const sut = new AddCharacterToPartyCommandHandler(
      new CharacterWriteModelFailingStub(),
      new PartyWriteModelFailingStub()
    )
    expect(sut.handle(command)).rejects.toThrow(RangeError)
  })
  test('It should throw error when character cannot be created', () => {
    const command = new AddCharacterToPartyCommand('01HWXMJQERR6M8DRDPWB2STFE0', '', 200)
    const sut = new AddCharacterToPartyCommandHandler(
      new CharacterWriteModelFailingStub(),
      new PartyWriteModelFailingStub()
    )
    expect(sut.handle(command)).rejects.toThrow(RangeError)
  })
  test('It should throw error when Character cannot be stored', () => {
    const command = new AddCharacterToPartyCommand('01HWXMNGAGJTG31HTVYWZBS7CR', 'Chindasvinto', 200)
    const sut = new AddCharacterToPartyCommandHandler(
      new CharacterWriteModelFailingStub(),
      new PartyWriteModelFailingStub()
    )
    expect(sut.handle(command)).rejects.toThrow(CharacterWriteModelError)
  })
  test('It should throw error when Character cannot be added to party', () => {
    const command = new AddCharacterToPartyCommand('01HWXMNGAGJTG31HTVYWZBS7CR', 'Chindasvinto', 200)
    const sut = new AddCharacterToPartyCommandHandler(
      new CharacterWriteModelSpy(),
      new PartyWriteModelFailingStub()
    )
    expect(sut.handle(command)).rejects.toThrow(PartyWriteModelError)
  })
  test('It should create and assign character properly', async () => {
    const command = new AddCharacterToPartyCommand('01HWXMNGAGJTG31HTVYWZBS7CR', 'Chindasvinto', 200)
    const characterSpy = new CharacterWriteModelSpy()
    const partySpy = new PartyWriteModelSpy()
    const sut = new AddCharacterToPartyCommandHandler(characterSpy, partySpy)
    await sut.handle(command)
    const storedCharacter = characterSpy.storedCharacter
    expect(storedCharacter).not.toBeNull()
    expect(partySpy.assignedCharacterUlid).toBe(storedCharacter?.ulid())
    expect(partySpy.toAssginPartyUlid).toBe('01HWXMNGAGJTG31HTVYWZBS7CR')
  })
})
