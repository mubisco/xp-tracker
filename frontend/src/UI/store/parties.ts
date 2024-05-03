import { defineStore } from 'pinia'
import { PartyDto } from '@/Domain/Party/PartyDto'
import { AllPartiesQuery } from '@/Application/Party/Query/AllPartiesQuery'
import { AllPartiesQueryHandlerProvider } from '@/Infrastructure/Party/Provider/AllPartiesQueryHandlerProvider'

export const usePartyStore = defineStore('party', {
  state: () => ({
    parties: [] as PartyDto[],
    noParties: false
  }),
  getters: {
    allParties: state => state.parties,
    areParties: state => state.noParties
  },
  actions: {
    async loadParties (): Promise<void> {
      const provider = new AllPartiesQueryHandlerProvider()
      const query = new AllPartiesQuery()
      const handler = provider.provide()
      const parties = await handler.handle(query)
      this.parties = parties
      this.noParties = parties.length === 0
    },
    async delayedLoadParties (delay: number): Promise<void> {
      setTimeout(() => { this.loadParties() }, delay)
    }
  }
})
