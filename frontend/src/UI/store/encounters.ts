import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { defineStore } from 'pinia'

export const useEncountersStore = defineStore('encounter', {
  state: () => ({
    encounters: [] as EncounterDto[]
  }),
  getters: {
    currentEncounters: state => state.encounters
  },
  actions: {
  }
})
