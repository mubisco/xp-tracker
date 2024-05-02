import { CreatePartyCommandHandler } from "@/Application/Party/Command/CreatePartyCommandHandler";
import { WithFetchPartyWriteModel } from "../Persistence/Fetch/WithFetchPartyWriteModel";

const baseUrl = import.meta.env.VITE_API_URL

export class CreatePartyCommandHandlerProvider {
  provide (): CreatePartyCommandHandler {
    const writeModel = new WithFetchPartyWriteModel(baseUrl)
    return new CreatePartyCommandHandler(writeModel)
  }
}
