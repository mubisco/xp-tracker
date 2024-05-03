import { EncounterForPartyWriteModel } from '@/Domain/Encounter/EncounterForPartyWriteModel'
import { EncounterWriteModelError } from '@/Domain/Encounter/EncounterWriteModelError'
import { SimpleEncounter } from '@/Domain/Encounter/SimpleEncounter'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class WithFetchEncounterClient implements EncounterForPartyWriteModel {
  // eslint-disable-next-line
  constructor(private readonly baseUrl: string) {}

  async createForParty (partyUlid: Ulid, encounter: SimpleEncounter): Promise<void> {
    await this.createEncounter(encounter)
    await this.addToParty(partyUlid, encounter.ulid())
  }

  private async createEncounter (encounter: SimpleEncounter): Promise<void> {
    const data = { ulid: encounter.ulid(), name: encounter.name() }
    const url = `${this.baseUrl}/encounter`
    const response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    if (!response.ok) {
      const content = await response.text()
      throw new EncounterWriteModelError(content)
    }
  }

  private async addToParty (partyUlid: Ulid, encounterUlid: string): Promise<void> {
    const url = `${this.baseUrl}/encounter/${encounterUlid}/assign-party/${partyUlid.value()}`
    const response = await fetch(url, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' }
    })
    if (!response.ok) {
      const content = await response.text()
      throw new EncounterWriteModelError(content)
    }
  }
}
