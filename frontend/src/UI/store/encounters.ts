import { CreateEncounterForPartyCommand } from '@/Application/Encounter/Command/CreateEncounterForPartyCommand'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { CreateEncounterForPartyCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/CreateEncounterForPartyCommandHandlerProvider'
import { defineStore } from 'pinia'

export const useEncountersStore = defineStore('encounter', {
  state: () => ({
    encounters: [] as EncounterDto[]
  }),
  getters: {
    currentEncounters: state => state.encounters
  },
  actions: {
    async addEncounterToParty (partyUlid: string, encounterName: string): Promise<void> {
      const provider = new CreateEncounterForPartyCommandHandlerProvider()
      const command = new CreateEncounterForPartyCommand(partyUlid, encounterName)
      const handler = provider.provide()
      await handler.handle(command)
      setTimeout(() => { this.loadEncounters(partyUlid) }, 500)
    },
    async loadEncounters (partyUlid: string): Promise<void> {
      const url = `http://localhost:5000/api/encounter/${partyUlid}`
      const response = await fetch(url, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
      })
      const parsedData = await response.json()
      this.encounters = parsedData
    }
  }
})
