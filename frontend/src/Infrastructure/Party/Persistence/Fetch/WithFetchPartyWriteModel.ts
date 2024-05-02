import { Party } from '@/Domain/Party/Party'
import { PartyWriteModel } from '@/Domain/Party/PartyWriteModel'
import { PartyWriteModelError } from '@/Domain/Party/PartyWriteModelError'

export class WithFetchPartyWriteModel implements PartyWriteModel{
  // eslint-disable-next-line
  constructor(private readonly baseUrl: string) {}

  async storeParty(party: Party): Promise<void> {
    const data = { ulid: party.id(), name: party.name() }
    const url = this.baseUrl + '/party'
    const response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!response.ok) {
      const content = await response.text()
      throw new PartyWriteModelError(content)
    }
  }
}
