import { AllPartiesQueryHandler } from '@/Application/Party/Query/AllPartiesQueryHandler'
import { FetchPartyClient } from '@/Infrastructure/Party/Persistence/Fetch/FetchPartyClient'

const baseUrl = import.meta.env.VITE_API_URL

export class AllPartiesQueryHandlerProvider {
  provide (): AllPartiesQueryHandler {
    const client = new FetchPartyClient(baseUrl)
    return new AllPartiesQueryHandler(client)
  }
}
