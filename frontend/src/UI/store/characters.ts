import { defineStore } from 'pinia'
import { SimpleCharacter } from '@/Domain/Party/Character/SimpleCharacter'
import { CharactersByPartyIdQueryHandlerProvider } from '@/Infrastructure/Party/Provider/CharactersByPartyIdQueryHandlerProvider'
import { CharactersByPartyIdQuery } from '@/Application/Party/Query/Character/CharactersByPartyIdQuery'
import { AddCharacterToPartyCommandHandlerProvider } from '@/Infrastructure/Party/Provider/AddCharacterToPartyCommandHandlerProvider'
import { AddCharacterToPartyCommand } from '@/Application/Party/Command/Character/AddCharacterToPartyCommand'

export const useCharacterStore = defineStore('character', {
  state: () => ({
    characters: [] as SimpleCharacter[]
  }),
  getters: {
    currentCharacters: state => state.characters
  },
  actions: {
    async loadCharacters (partyUlid: string): Promise<void> {
      const provider = new CharactersByPartyIdQueryHandlerProvider()
      const query = new CharactersByPartyIdQuery(partyUlid)
      const handler = provider.provide()
      const result = await handler.handle(query)
      this.characters = result
    },
    async createCharacter (partyUlid: string, characterName: string, xp: number): Promise<void> {
      const provider = new AddCharacterToPartyCommandHandlerProvider()
      const command = new AddCharacterToPartyCommand(partyUlid, characterName, xp)
      const handler = provider.provide()
      await handler.handle(command)
      setTimeout(() => { this.loadCharacters(partyUlid) }, 750)
    }
  }
})
