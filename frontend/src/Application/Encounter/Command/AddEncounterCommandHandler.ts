import { AddEncounterCommand } from './AddEncounterCommand'
import { AddEncounterWriteModel } from '@/Domain/Encounter/AddEncounterWriteModel'
import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { PartyTresholdsReadModel } from '@/Domain/Encounter/Party/PartyTresholdsReadModel'

export class AddEncounterCommandHandler {
  // eslint-disable-next-line
  constructor (
    private readonly writeModel: AddEncounterWriteModel,
    private readonly partyTresholdReadModel: PartyTresholdsReadModel
  ) {}

  async handle (command: AddEncounterCommand): Promise<void> {
    const encounter = DomainEncounter.withName(EncounterName.fromString(command.name))
    const partyTresholds = await this.partyTresholdReadModel.fetchTresholds()
    encounter.updateLevel(partyTresholds)
    return this.writeModel.write(encounter)
  }
}
