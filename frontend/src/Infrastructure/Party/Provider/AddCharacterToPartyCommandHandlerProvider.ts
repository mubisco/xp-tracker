import { AddCharacterToPartyCommandHandler } from '@/Application/Party/Command/Character/AddCharacterToPartyCommandHandler'
import { FetchPartyClient } from '../Persistence/Fetch/FetchPartyClient'
import { FetchCharacterClient } from '../Persistence/Fetch/FetchCharacterClient'

const baseUrl = import.meta.env.VITE_API_URL

export class AddCharacterToPartyCommandHandlerProvider {
  provide (): AddCharacterToPartyCommandHandler {
    const writeModel = new FetchCharacterClient(baseUrl)
    const updateModel = new FetchPartyClient(baseUrl)
    return new AddCharacterToPartyCommandHandler(writeModel, updateModel)
  }
}
