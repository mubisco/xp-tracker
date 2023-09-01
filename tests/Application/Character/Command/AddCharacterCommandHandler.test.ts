import { describe, test, expect } from 'vitest'
import { AddCharacterCommandHandler } from '@/Application/Character/Command/AddCharacterCommandHandler'
import { CharacterFactoryError } from '@/Domain/Character/CharacterFactoryError'
import { CharacterRepositoryError } from '@/Domain/Character/CharacterRepositoryError'
import { FailingCharacterFactory } from './FailingCharacterFactory'
import { MockedCharacterFactory } from './MockedCharacterFactory'
import { FailingCharacterRepository } from './FailingCharacterRepository'
import { MockedCharacterRepository } from './MockedCharacterRepository'
import { AddCharacterCommand } from '@/Application/Character/Command/AddCharacterCommand'

const command = new AddCharacterCommand({ name: 'asd' })

describe('Testing AddCharacterCommandHandler', () => {
  test('It should throw error when wrong data', () => {
    const repository = new MockedCharacterRepository()
    const sut = new AddCharacterCommandHandler(new FailingCharacterFactory(), repository)
    expect(sut.handle(command)).rejects.toThrow(CharacterFactoryError)
  })
  test('It should throw error when character cannot be created', () => {
    const repository = new FailingCharacterRepository()
    const sut = new AddCharacterCommandHandler(new MockedCharacterFactory(), repository)
    expect(sut.handle(command)).rejects.toThrow(CharacterRepositoryError)
  })
  test('It should store character', async () => {
    const repository = new MockedCharacterRepository()
    const sut = new AddCharacterCommandHandler(new MockedCharacterFactory(), repository)
    await sut.handle(new AddCharacterCommand({ name: 'asd' }))
    expect(repository.character).not.toBeNull()
  })
})
