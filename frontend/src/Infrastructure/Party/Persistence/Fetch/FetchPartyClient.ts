import { Party } from '@/Domain/Party/Party'
import { PartyDto } from '@/Domain/Party/PartyDto'
import { PartyReadModel } from '@/Domain/Party/PartyReadModel'
import { PartyReadModelError } from '@/Domain/Party/PartyReadModelError'
import { PartyWriteModel } from '@/Domain/Party/PartyWriteModel'
import { PartyWriteModelError } from '@/Domain/Party/PartyWriteModelError'

export class FetchPartyClient implements PartyWriteModel, PartyReadModel {
  // eslint-disable-next-line
  constructor(private readonly baseUrl: string) {}
  async allParties (): Promise<PartyDto[]> {
    const url = this.baseUrl + '/party'
    const response = await fetch(url, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' }
    })
    if (!response.ok) {
      const content = await response.text()
      throw new PartyReadModelError(content)
    }
    const content = await response.json()
    return content
  }

  async storeParty (party: Party): Promise<void> {
    const data = { ulid: party.id(), name: party.name() }
    const url = this.baseUrl + '/party'
    const response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    if (!response.ok) {
      const content = await response.text()
      throw new PartyWriteModelError(content)
    }
  }
}
