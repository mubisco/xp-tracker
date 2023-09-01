import { CharacterFactory } from '@/Domain/Character/CharacterFactory'
import { AddCharacterCommand } from './AddCharacterCommand'
import { CharacterRepository } from '@/Domain/Character/CharacterRepository'

export class AddCharacterCommandHandler {
  private _factory: CharacterFactory
  private _repository: CharacterRepository

  constructor (factory: CharacterFactory, repository: CharacterRepository) {
    this._factory = factory
    this._repository = repository
  }

  async handle (command: AddCharacterCommand): Promise<void> {
    const character = this._factory.make(command.data)
    await this._repository.store(character)
  }
}
