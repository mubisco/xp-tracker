import { AddCharacterToPartyCommand } from './AddCharacterToPartyCommand'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'
import { Character } from '@/Domain/Party/Character/Character'
import { CreateCharacterWriteModel } from '@/Domain/Party/Character/CreateCharacterWriteModel'
import { AssignCharacterToPartyWriteModel } from '@/Domain/Party/AssignCharacterToPartyWriteModel'

export class AddCharacterToPartyCommandHandler {
  // eslint-disable-next-line
  constructor(
    private readonly writeModel: CreateCharacterWriteModel,
    private readonly partyUpdateModel: AssignCharacterToPartyWriteModel
  ) {}

  async handle (command: AddCharacterToPartyCommand): Promise<void> {
    const partyUlid = Ulid.fromString(command.partyUlid)
    const character = Character.withNameAndXp(command.characterName, command.experiencePoints)
    await this.writeModel.createCharacter(character)
    const characterUlid = Ulid.fromString(character.ulid())
    await this.partyUpdateModel.assingnToParty(characterUlid, partyUlid)
  }
}
