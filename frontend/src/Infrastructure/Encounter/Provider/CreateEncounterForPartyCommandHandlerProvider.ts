import { CreateEncounterForPartyCommandHandler } from '@/Application/Encounter/Command/CreateEncounterForPartyCommandHandler'
import { WithFetchEncounterClient } from '../Persistence/Fetch/WithFetchEncounterClient'

const baseUrl = import.meta.env.VITE_API_URL

export class CreateEncounterForPartyCommandHandlerProvider {
  provide (): CreateEncounterForPartyCommandHandler {
    const writeModel = new WithFetchEncounterClient(baseUrl)
    return new CreateEncounterForPartyCommandHandler(writeModel)
  }
}
