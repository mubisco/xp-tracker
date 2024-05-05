import { defineStore } from 'pinia'
import { SimpleCharacter } from '@/Domain/Party/Character/SimpleCharacter'
import { CharactersByPartyIdQueryHandlerProvider } from '@/Infrastructure/Party/Provider/CharactersByPartyIdQueryHandlerProvider'
import { CharactersByPartyIdQuery } from '@/Application/Party/Query/Character/CharactersByPartyIdQuery'
import { AddCharacterToPartyCommandHandlerProvider } from '@/Infrastructure/Party/Provider/AddCharacterToPartyCommandHandlerProvider'
import { AddCharacterToPartyCommand } from '@/Application/Party/Command/Character/AddCharacterToPartyCommand'

const LOAD_DELAY = 1000
const baseUrl = import.meta.env.VITE_API_URL

export const useCharacterStore = defineStore('character', {
  state: () => ({
    characters: [] as SimpleCharacter[],
    loading: false
  }),
  getters: {
    currentCharacters: state => state.characters,
    loadingCharacters: state => state.loading
  },
  actions: {
    async loadCharacters (partyUlid: string): Promise<void> {
      this.loading = true
      const provider = new CharactersByPartyIdQueryHandlerProvider()
      const query = new CharactersByPartyIdQuery(partyUlid)
      const handler = provider.provide()
      const result = await handler.handle(query)
      this.characters = result
      this.loading = false
    },
    async createCharacter (partyUlid: string, characterName: string, xp: number): Promise<void> {
      this.loading = true
      const provider = new AddCharacterToPartyCommandHandlerProvider()
      const command = new AddCharacterToPartyCommand(partyUlid, characterName, xp)
      const handler = provider.provide()
      await handler.handle(command)
      setTimeout(() => { this.loadCharacters(partyUlid) }, LOAD_DELAY)
    },
    async deleteCharacter (partyUlid: string, characterUlid: string): Promise<void> {
      this.loading = true
      const url = `${baseUrl}/party/${partyUlid}/remove/${characterUlid}`
      await fetch(url, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
      })
      setTimeout(() => { this.loadCharacters(partyUlid) }, LOAD_DELAY)
    }
  }
})
