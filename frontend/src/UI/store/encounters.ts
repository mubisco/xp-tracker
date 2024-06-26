import { CreateEncounterForPartyCommand } from '@/Application/Encounter/Command/CreateEncounterForPartyCommand'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { CreateEncounterForPartyCommandHandlerProvider } from '@/Infrastructure/Encounter/Provider/CreateEncounterForPartyCommandHandlerProvider'
import { defineStore } from 'pinia'

const LOAD_DELAY = 1000
const baseUrl = import.meta.env.VITE_API_URL

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
      setTimeout(() => { this.loadEncounters(partyUlid) }, LOAD_DELAY)
    },
    async loadEncounters (partyUlid: string): Promise<void> {
      const url = `${baseUrl}/encounter/${partyUlid}`
      const response = await fetch(url, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
      })
      const parsedData = await response.json()
      this.encounters = parsedData
    },
    async addMonster (
      partyUlid: string,
      encounterUlid: string,
      monsterName: string,
      challengeRating: string
    ) {
      const url = `${baseUrl}/encounter/monster/add`
      const data = { encounterUlid, monsterName, challengeRating }
      await fetch(url, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      setTimeout(() => { this.loadEncounters(partyUlid) }, LOAD_DELAY)
    },
    async removeMonster (
      partyUlid: string,
      encounterUlid: string,
      monsterName: string,
      challengeRating: string
    ) {
      const url = `${baseUrl}/encounter/monster/remove`
      const data = { encounterUlid, monsterName, challengeRating }
      await fetch(url, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      setTimeout(() => { this.loadEncounters(partyUlid) }, LOAD_DELAY)
    },
    async resolveEncounter (partyUlid: string, encounterUlid: string) {
      const url = `${baseUrl}/encounter/resolve`
      const data = { encounterUlid }
      await fetch(url, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      setTimeout(() => { this.loadEncounters(partyUlid) }, LOAD_DELAY)
    },
    async deleteEncounter (partyUlid: string, encounterUlid: string) {
      const url = `${baseUrl}/encounter/delete/${encounterUlid}`
      await fetch(url, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
      })
      setTimeout(() => { this.loadEncounters(partyUlid) }, LOAD_DELAY)
    }
  }
})
