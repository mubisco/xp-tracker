import { beforeEach, describe, test, expect } from 'vitest'
import { AddCharacterCommandHandler } from '@/Application/Character/Command/AddCharacterCommandHandler'
import { AddCharacterCommand } from '@/Application/Character/Command/AddCharacterCommand'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { AddCharacterWriteModelSpy } from './AddCharacterWriteModelSpy'

let sut: AddCharacterCommandHandler
let writeModel: AddCharacterWriteModelSpy

describe('Testing AddCharacterCommandHandler', () => {
  beforeEach(() => {
    writeModel = new AddCharacterWriteModelSpy()
    sut = new AddCharacterCommandHandler(writeModel)
  })
  test('It should throw error when wrong name', () => {
    const command = new AddCharacterCommand('', 325, 25)
    expect(sut.handle(command)).rejects.toThrow(RangeError)
  })
  test('It should throw error when character cannot be created', () => {
    writeModel.shouldFail = true
    const command = new AddCharacterCommand('Darling', 325, 25)
    expect(sut.handle(command)).rejects.toThrow(CharacterWriteModelError)
  })
  test('It should store character', async () => {
    const command = new AddCharacterCommand('Darling', 325, 25)
    await sut.handle(command)
    expect(writeModel.wasAdded()).toBe(true)
  })
})
