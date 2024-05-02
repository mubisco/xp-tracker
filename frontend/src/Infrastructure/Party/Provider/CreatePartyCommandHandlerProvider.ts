import { CreatePartyCommandHandler } from '@/Application/Party/Command/CreatePartyCommandHandler'
import { FetchPartyClient } from '@/Infrastructure/Party/Persistence/Fetch/FetchPartyClient'

const baseUrl = import.meta.env.VITE_API_URL

export class CreatePartyCommandHandlerProvider {
  provide (): CreatePartyCommandHandler {
    const writeModel = new FetchPartyClient(baseUrl)
    return new CreatePartyCommandHandler(writeModel)
  }
}
