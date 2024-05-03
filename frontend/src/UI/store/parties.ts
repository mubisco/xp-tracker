import { defineStore } from 'pinia'
import { PartyDto } from '@/Domain/Party/PartyDto'
import { AllPartiesQuery } from '@/Application/Party/Query/AllPartiesQuery'
import { AllPartiesQueryHandlerProvider } from '@/Infrastructure/Party/Provider/AllPartiesQueryHandlerProvider'
import { CreatePartyCommandHandlerProvider } from '@/Infrastructure/Party/Provider/CreatePartyCommandHandlerProvider'
import { CreatePartyCommand } from '@/Application/Party/Command/CreatePartyCommand'

export const usePartyStore = defineStore('party', {
  state: () => ({
    parties: [] as PartyDto[],
    currentPartyUlid: '',
    currentParty: undefined as PartyDto | undefined,
    noParties: false
  }),
  getters: {
    allParties: state => state.parties,
    areParties: state => state.noParties,
    activePartyUlid: state => state.currentPartyUlid,
    activeParty: state => state.currentParty
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
    async createParty (partyName: string): Promise<void> {
      const provider = new CreatePartyCommandHandlerProvider()
      const handler = provider.provide()
      const command = new CreatePartyCommand(partyName)
      await handler.handle(command)
      setTimeout(() => { this.loadParties() }, 750)
    },
    selectParty (partyUlid: string): void {
      this.currentPartyUlid = partyUlid
      this.currentParty = this.parties.find((party: PartyDto) => {
        return party.partyUlid === partyUlid
      })
    }
  }
})
