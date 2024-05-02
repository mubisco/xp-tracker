import { Party } from "@/Domain/Party/Party";
import { CreatePartyCommand } from "./CreatePartyCommand";
import { PartyWriteModel } from "@/Domain/Party/PartyWriteModel";

export class CreatePartyCommandHandler {
  // eslint-disable-next-line
  constructor(private readonly writeModel: PartyWriteModel) {
  }
  async handle(command: CreatePartyCommand): Promise<void> {
    const party = Party.fromName(command.name)
    await this.writeModel.storeParty(party)
    return Promise.resolve()
  }
}
